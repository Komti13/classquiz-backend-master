<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Frame;
use App\Http\Resources\Frame as FrameResource;

/**
 * @resource Frames
 *
 */
class FrameController extends Controller
{
    /**
     * Display a listing of the frame.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FrameResource::collection(Frame::all());
    }

    /**
     * Store a newly created frame in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        $frame = new Frame;
        $frame->name = request('name');
        $frame->user_id = request('user_id');

        $imageName = time() . '.' . request('image')->getClientOriginalExtension();
        request('image')->move(public_path('uploads/frame/'), $imageName);
        $frame->image = $imageName;

        $frame->save();


        return new FrameResource($frame);
    }

    /**
     * Display the specified frame.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new FrameResource(Frame::findOrFail($id));
    }

    /**
     * Update the specified frame in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $frame = Frame::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|integer|exists:users,id',

        ]);

        $frame->name = request('name');
        $frame->user_id = request('user_id');

        if (request('icon')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/frame/'), $imageName);
            $frame->image = $imageName;
        }

        $frame->save();

        return new FrameResource($frame);

    }

    /**
     * Remove the specified frame from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $frame = Frame::destroy($id);
        return response()->json([
            'message' => 'Frame deleted successfully'
        ], 200);
    }
}
