<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classroom;
use App\User;
use App\Http\Resources\Classroom as ClassroomResource;
use App\Http\Resources\ClassroomTeacher as ClassroomTeacherResource;
use App\Http\Resources\ClassroomStudent as ClassroomStudentResource;

/**
 * @resource Classrooms
 *
 */
class ClassroomController extends Controller
{
    /**
     * Display a listing of the classroom.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClassroomResource::collection(Classroom::all());
    }

    /**
     * Store a newly created classroom in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|integer|exists:levels,id',

        ]);
        $classroom = new Classroom;
        $classroom->name = request('name');
        $classroom->level_id = request('level_id');

        $classroom->save();

        return new ClassroomResource($classroom);
    }

    /**
     * Display the specified classroom.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ClassroomResource(Classroom::findOrFail($id));
    }

    /**
     * Update the specified classroom in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $classroom = Classroom::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'level_id' => 'required|integer|exists:levels,id',

        ]);
        $classroom->name = request('name');
        $classroom->level_id = request('level_id');

        $classroom->save();

        return new ClassroomResource($classroom);

    }

    /**
     * Remove the specified classroom from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $classroom = Classroom::destroy($id);
        return response()->json([
            'message' => 'Classroom deleted successfully'
        ], 200);
    }

    /**
     * Add a student to classroom.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function addStudentToClassroom()
    {
        request()->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'student_id' => 'required|exists:users,id'
        ]);

        $student = User::findOrFail(request('student_id'));
        if (!$student->hasRole('STUDENT')) {
            return abort(404, 'Student not found');
        }
        $student->classrooms()->attach(request('classroom_id'));

        return response()->json([
            'message' => 'Student added to classroom successfully'
        ], 200);
    }

    /**
     * Remove student from classroom.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function removeStudentFromClassroom($classroomId, $studentId)
    {
        $student = User::findOrFail($studentId);
        $student->classrooms()->detach($classroomId);

        return response()->json([
            'message' => 'Student removed from classroom successfully'
        ], 200);
    }

    /**
     * List classroom students.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listClassroomStudents($classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);
        return new ClassroomStudentResource($classroom);
    }

    /**
     * Add a teacher to classroom.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function addTeacherToClassroom()
    {
        request()->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $teacher = User::findOrFail(request('teacher_id'));
        if (!$teacher->hasRole('TEACHER')) {
            return abort(404, 'Teacher not found');
        }

        $teacher->classrooms()->sync([request('classroom_id') => [
            'subject_id' => request('subject_id')
        ]], false);

        return response()->json([
            'message' => 'Teacher added to classroom successfully'
        ], 200);
    }

    /**
     * Remove teacher from classroom.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function removeTeacherFromClassroom($classroomId, $teacherId)
    {
        $teacher = User::findOrFail($teacherId);
        $teacher->classrooms()->detach($classroomId);

        return response()->json([
            'message' => 'Teacher removed from classroom successfully'
        ], 200);
    }

    /**
     * List classroom teachers.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listClassroomTeachers($classroomId)
    {
        $classroom = Classroom::findOrFail($classroomId);
        return new ClassroomTeacherResource($classroom);
    }

}
