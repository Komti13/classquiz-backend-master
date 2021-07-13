<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Role;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Users
 *
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['findByEmail', 'findByPhone']);

        $this->middleware('role:TEACHER,SCHOOL_ADMIN')->only('students');
    }

    /**
     * Display a listing of the user.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created user in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $role)
    {
        if ($role == 'PARENT') {
            $request->validate([
                'name' => 'nullable|string',
                'phone' => 'required|string|unique:users',
                'address' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'avatar_id' => 'nullable|integer|exists:avatars,id',
                'school' => 'nullable|string',
                'school_id' => 'nullable|exists:schools,id',
                'country_id' => 'nullable|exists:countries,id',
                'level_id' => 'nullable|exists:levels,id',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|confirmed'
            ]);
        } elseif ($role == 'STUDENT') {
            $request->validate([
                'name' => 'required|string',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'avatar_id' => 'nullable|integer|exists:avatars,id',
                'school' => 'nullable|string',
                'school_id' => 'nullable|exists:schools,id',
                'country_id' => 'nullable|exists:countries,id',
                'level_id' => 'required|exists:levels,id',
                'email' => 'nullable|string|email',
                'password' => 'required|string|confirmed'
            ]);
        } elseif ($role == 'TEACHER') {
            $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string',
                //'school' => 'nullable|string',
                'school_id' => 'required|exists:schools,id',
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed'
            ]);
        } elseif ($role == 'SCHOOL_ADMIN') {
            $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string',
                'school_id' => 'required|exists:schools,id',
                'email' => 'required|string|email',
                'password' => 'required|string|confirmed'
            ]);
        }
        $user = new User;
        $user->name = request('name');
        $user->phone = request('phone');
        $user->address = request('address');
        $user->school = request('school');
        $user->school_id = request('school_id');
        $user->country_id = request('country_id');
        $user->email = strtolower(request('email'));
        $user->password = bcrypt(request('password'));
        if (request('image')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/user/'), $imageName);
            $user->image = $imageName;
        }

        $user->avatar_id = request('avatar_id');

        if (request('enabled')) {
            $user->enabled = request('enabled');
        } else {
            $user->enabled = 1;
        }
        $user->save();

        $user
            ->roles()
            ->attach(Role::where('name', $role)->first());

        return new UserResource($user);
    }

    /**
     * Display the specified user.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Update the specified user in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        request()->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'avatar_id' => 'nullable|integer|exists:avatars,id',
            'school' => 'nullable|string',
            'school_id' => 'nullable|exists:schools,id',
            'country_id' => 'nullable|exists:countries,id',
            'level_id' => 'nullable|exists:levels,id',
            'email' => 'nullable|string|email',
            'enabled' => 'boolean',
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|string|min:3|confirmed',
        ]);

        if (request('current_password') && request('new_password')) {

            if (!(Hash::check(request('current_password'), $user->password))) {
                // The passwords matches
                return response()->json([
                    'message' => "Your current password does not matches with the password you provided. Please try again."
                ], 422);

            }
            if (strcmp(request('current_password'), request('new_password')) == 0) {
                //Current password and new password are same
                return response()->json([
                    'message' => "New Password cannot be same as your current password. Please choose a different password."
                ], 422);
            }

            request()->validate([

            ]);

            $user->password = bcrypt(request('new_password'));

        }

        $user->name = request('name') ? request('name') : $user->name;
        $user->phone = request('phone') ? request('phone') : $user->phone;
        $user->address = request('address') ? request('address') : $user->address;
        $user->school = request('school') ? request('school') : $user->school;
        $user->school_id = request('school_id') ? request('school_id') : $user->school_id;
        $user->country_id = request('country_id') ? request('country_id') : $user->country_id;
        $user->email = request('email') ? strtolower(request('email')) : $user->email;

        if (request('image')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/user/'), $imageName);
            $user->image = $imageName;
        }

        $user->avatar_id = request('avatar_id');

        if (request('enabled')) {
            $user->enabled = request('enabled');
        } else {
            $user->enabled = 1;
        }

        $user->save();

        return new UserResource($user);
    }

    /**
     * Remove the specified user from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::destroy($id);
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function findByEmail($email)
    {
        $users = User::where('email', $email)->get();
        if ($users->count()) {
            return UserResource::collection($users);
        }
        return response()->json([
            'message' => 'Users not found',
        ], 404);
    }

    public function findByPhone($phone)
    {
        $users = User::where('phone', $phone)->get();
        if ($users->count()) {
            return UserResource::collection($users);
        }
        return response()->json([
            'message' => 'Users not found',
        ], 404);
    }

    /**
     * Display a listing of students with the same school of connected teacher/school admin by level.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function students($levelId)
    {
        $user = Auth::guard('api')->user();
        $students = User::where('level_id', $levelId)
            ->where('school_id', $user->school_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'STUDENT');
            })->get();
        return UserResource::collection($students);
    }

    /**
     * Display a listing of teachers with the same school of connected school admin.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function teachers()
    {
        $user = Auth::guard('api')->user();
        $teachers = User::where('school_id', $user->school_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'TEACHER');
            })->get();
        return UserResource::collection($teachers);
    }
}