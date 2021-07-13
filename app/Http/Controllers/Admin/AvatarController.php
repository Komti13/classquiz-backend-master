<?php

namespace App\Http\Controllers\Admin;

use App\Avatar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $avatars = Avatar::all();
        return view('admin.avatar.index', compact('avatars'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|image',
        ]);
        $avatar = new Avatar;
        if (request('name')) {
            $imageName = time() . '.' . request('name')->getClientOriginalExtension();
            request('name')->move(public_path('uploads/avatar/'), $imageName);
            $avatar->name = $imageName;
        }
        $avatar->save();

        return response()->json([
            "name" => $avatar->name,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($name)
    {
        Avatar::whereName($name)->delete();
        return response()->json([
            "success" => true,
        ]);
    }
}
