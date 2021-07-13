<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Admin;

class AdminController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $admins = Admin::query();
            return datatables()->eloquent($admins)->toJson();
        }

        return view('admin.admin.index');
    }

    public function create()
    {
        return view('admin.admin.create');
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6|confirmed',
            'enabled' => 'boolean'
        ]);
        $admin = new Admin;
        $admin->name = request('name');
        $admin->email = request('email');
        $admin->enabled = request('enabled') ? 1 : 0;
        $admin->password = bcrypt(request('password'));
        $admin->save();


        return redirect()->route('admins.index')
            ->with('success', 'Admin created successfully');
    }

    public function edit($id)
    {

        $admin = Admin::findOrFail($id);
        return view('admin.admin.edit', compact('admin'));
    }


    public function update($id)
    {
        $admin = Admin::findOrFail($id);
        request()->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
            'enabled' => 'nullable|boolean'
        ]);
        $admin->name = request('name');
        $admin->email = request('email');
        // if(!request('enabled')) {
        // return redirect()->route('admins.index')
        //                       ->with('error','We need at least one admin with an activated account');
        // }
        $admin->enabled = request('enabled') ? 1 : 0;
        if (request('password')) {
            $admin->password = bcrypt(request('password'));
        }
        $admin->save();
        return redirect()->route('admins.index')
            ->with('success', 'Admin edited successfully');
    }


    public function destroy($id)
    {
        if (Admin::all()->count() == 1) {
            return redirect()->route('admins.index')
                ->with('error', 'We must have at least one administrator');
        }
        Admin::destroy($id);
        session()->flash('success', 'Admin deleted successfully');

        return response()->json(['success' => true]);
    }
}
