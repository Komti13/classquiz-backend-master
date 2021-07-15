<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pack;
use App\User;
use App\Role;
use App\School;
use App\Country;
use App\Level;
use function foo\func;

class UserController extends Controller
{
    public function __construct()
    {
        $role = request()->route('role');
        if ($role && !Role::where('name', $role)->first()) {
            abort(404);
        }
    }

    public function index($role)
    {
        $role = Role::where('name', $role)->first();
        $packs = Pack::where('enabled', 1)->groupBy('name')->get('name');
        $levels = Level::all();
        if (request()->isXmlHttpRequest()) {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role->name);
            })
                ->with('parent', 'students', 'achivement', 'validSubscriptions', 'level')
                ->when(request('subscription'), function ($q) {
                    $q->whereHas('validSubscriptions.pack', function ($q) {
                        $q->where('name', 'like', '%' . request('subscription') . '%');
                    });
                })->when(request('created_at_from'), function ($q) {
                    $q->where('created_at', '>=', request('created_at_from'));
                })->when(request('created_at_to'), function ($q) {
                    $q->where('created_at', '<=', request('created_at_to'));
                })->when(request('level'), function ($q) {
                    $q->where('level_id', request('level'));
                });

            return datatables()->eloquent($users)->toJson();
        }

        return view('admin.user.index', compact('role', 'packs', 'levels'));
    }

    public function show($role, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::where('name', $role)->first();
        return view('admin.user.show', compact('user', 'role'));
    }

    public function create($role)
    {
        $role = Role::where('name', $role)->first();
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        return view('admin.user.create', compact('schools', 'countries', 'role', 'levels'));
    }

    public function store($role)
    {
        request()->validate([
            'name' => 'required|string',
            'phone' => 'string|unique:users',
            'address' => 'nullable|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'school' => 'nullable|string',
            'school_id' => 'exists:schools,id',
            'country_id' => 'exists:countries,id',
            'level_id' => 'exists:levels,id',
            'email' => 'nullable|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'enabled' => 'boolean',
        ]);
        $user = new User;
        $user->name = request('name');
        $user->phone = request('phone');
        $user->address = request('address');
        $user->school = request('school');
        $user->school_id = request('school_id');
        $user->level_id = request('level_id');
        $user->country_id = request('country_id');
        $user->email = strtolower(request('email'));
        $user->password = bcrypt(request('password'));
        if (request('image')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/user/'), $imageName);
            $user->image = $imageName;
        }

        $user->enabled = request('enabled') ? 1 : 0;
        $user->active = true;


        $user->save();

        $role = Role::where('name', $role)->first();
        $user->roles()->attach($role->id);


        return redirect()->route('users.index', ['role' => $role->name])
            ->with('success', 'User created successfully');
    }

    public function edit($role, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::where('name', $role)->first();
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        return view('admin.user.edit', compact('user', 'schools', 'countries', 'levels', 'role'));
    }


    public function update($role, $id)
    {
        $user = User::findOrFail($id);
        request()->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'school' => 'nullable|string',
            'school_id' => 'exists:schools,id',
            'country_id' => 'exists:countries,id',
            'level_id' => 'exists:levels,id',
            'email' => 'nullable|string|email',
            'password' => 'nullable|string',
            'enabled' => 'boolean',
        ]);

        $email = strtolower(request('email'));
        $phone = strtolower(request('phone'));
        if ($role === 'PARENT') {
            if ($email && $email !== $user->email) {
                $emailExist = User::where('id', '<>', $user->id)->where('email', $email)->whereHas('roles', function ($q) {
                    $q->where('name', 'PARENT');
                })->first();
                if ($emailExist) {
                    return back()->with('error', 'Email already taken');
                }
            }
            if ($phone && $phone !== $user->phone) {
                $phoneExist = User::where('id', '<>', $user->id)->where('phone', $phone)->whereHas('roles', function ($q) {
                    $q->where('name', 'PARENT');
                })->first();
                if ($phoneExist) {
                    return back()->with('error', 'Phone already taken');
                }
            }
        }
        $user->name = request('name');
        $user->phone = request('phone');
        $user->address = request('address');
        $user->school = request('school');
        $user->school_id = request('school_id');
        $user->level_id = request('level_id');
        $user->country_id = request('country_id');
        $user->email = strtolower(request('email'));
        if (request('password')) {
            $user->password = bcrypt(request('password'));
            $user->students()->update(['password' => $user->password]);
        }
        if (request('image')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/user/'), $imageName);
            $user->image = $imageName;
        }

        $user->enabled = request('enabled') ? 1 : 0;


        $user->save();

        $role = Role::where('name', $role)->first();

        return redirect()->route('users.index', ['role' => $role->name])
            ->with('success', 'User edited successfully');
    }


    public function destroy($role, $id)
    {
        User::destroy($id);
        session()->flash('success', 'User deleted successfully');

        return response()->json(['success' => true]);
    }
}