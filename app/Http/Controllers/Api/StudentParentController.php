<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\User as UserResource;

/**
 * @resource StudentParent
 *
 */
class StudentParentController extends Controller
{
    /**
     * Add student to parent
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addStudentToParent()
    {
        request()->validate([
            'parent_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $parent = User::findOrFail(request('parent_id'));
        if (!$parent->hasRole('PARENT')) {
            return abort(404, 'Parent not found');
        }
        $parent->students()->attach(request('student_id'));

        return response()->json([
            'message' => 'Student added to parent successfully'
        ], 200);
    }

    /**
     * find parent by student
     * @authenticated
     * @param  [int] studentId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function studentParent($studentId)
    {
        $student = User::findOrFail($studentId);
        if (!$student->hasRole('STUDENT')) {
            return abort(404, 'Student not found');
        }

        return UserResource::collection($student->parent);
    }

    /**
     * find students by parent
     * @authenticated
     * @param  [int] parentId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function parentStudents($parentId)
    {
        $parent = User::findOrFail($parentId);
        if (!$parent->hasRole('PARENT')) {
            return abort(404, 'Parent not found');
        }

        return UserResource::collection($parent->students);
    }

    /**
     * remove student from parent
     * @authenticated
     * @param  [int] parentId
     * @param  [int] studentId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeStudentFromParent($parentId, $studentId)
    {
        $parent = User::findOrFail($parentId);
        $parent->students()->detach($studentId);

        return response()->json([
            'message' => 'Student deleted from parent successfully'
        ], 200);
    }
}
