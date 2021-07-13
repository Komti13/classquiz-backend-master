<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Subject;
use App\Http\Resources\Subject as SubjectResource;

/**
 * @resource Subjects
 *
 */
class SubjectController extends Controller
{
    /**
     * Display a listing of the subject.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SubjectResource::collection(Subject::all());
    }

    /**
     * Store a newly created subject in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
        $subject = new Subject;
        $subject->name = request('name');
        $subject->order = request('order');

        $imageName = time() . '.' . request('image')->getClientOriginalExtension();
        request('image')->move(public_path('uploads/subject/'), $imageName);
        $subject->image = $imageName;

        $subject->save();

        return new SubjectResource($subject);
    }

    /**
     * Display the specified subject.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new SubjectResource(Subject::findOrFail($id));
    }

    /**
     * Update the specified subject in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        $subject->name = request('name');
        $subject->order = request('order');

        if (request('image')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/subject/'), $imageName);
            $subject->image = $imageName;
        }

        $subject->save();

        return new SubjectResource($subject);

    }

    /**
     * Remove the specified subject from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subject = Subject::destroy($id);
        return response()->json([
            'message' => 'Subject deleted successfully'
        ], 200);
    }

    /**
     * Display a listing of the subject by leve.
     * @authenticated
     * @param int $levelId
     * @return \Illuminate\Http\Response
     */
    public function listByLevel($levelId)
    {
        $subjects = Subject::whereHas('levels', function ($query) use ($levelId) {
            $query->where('level_id', $levelId);
        })->get();
        return SubjectResource::collection($subjects);
    }
}
