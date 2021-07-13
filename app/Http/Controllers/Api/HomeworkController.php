<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Homework;
use App\Http\Resources\Homework as HomeworkResource;

/**
 * @resource Homework
 *
 */
class HomeworkController extends Controller
{
    /**
     * Display a listing of homeworks.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created homework in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'question_group_id' => 'required|integer|exists:question_groups,id',
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'required',
            // 'score' => 'required',
            // 'time' => 'required'
        ]);
        $homework = new Homework;
        $homework->question_group_id = request('question_group_id');
        $homework->user_id = request('user_id');
        $homework->status = request('status');
        // $homework->score = request('score');
        // $homework->time = request('time');
        $homework->score = 0;
        $homework->time = 0;

        $homework->save();

        return new HomeworkResource($homework);
    }

    /**
     * Display the specified homework.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new HomeworkResource(Homework::findOrFail($id));
    }

    /**
     * Update the specified homework in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        request()->validate([
            'question_group_id' => 'required|integer|exists:question_groups,id',
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'required',
            // 'score' => 'required',
            // 'time' => 'required'
        ]);
        $homework = Homework::findOrFail($id);
        $homework->question_group_id = request('question_group_id');
        $homework->user_id = request('user_id');
        $homework->status = request('status');
        // $homework->score = request('score');
        // $homework->time = request('time');
        $homework->score = 0;
        $homework->time = 0;

        $homework->save();

        return new HomeworkResource($homework);
    }

    /**
     * Remove the specified homework from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $homework = Homework::destroy($id);
        return response()->json([
            'message' => 'Homework deleted successfully'
        ], 200);
    }

    /**
     * List homeworks by classroom.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function listByClassroom($classroomId)
    {
        $homeworks = Homework::with(['user' => function ($q) use ($classroomId) {
            $q->whereHas('classrooms', function ($q) use ($classroomId) {
                $q->where('id', $classroomId);
            });
        }])
            ->get();

        return HomeworkResource::collection($homeworks);
    }
}