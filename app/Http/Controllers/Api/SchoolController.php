<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\School;
use App\Http\Resources\School as SchoolResource;
use App\Http\Resources\User as UserResource;

/**
 * @resource Schools
 *
 */
class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'role:SCHOOL_ADMIN'])->only('students');
    }

    /**
     * Display a listing of the school.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SchoolResource::collection(School::all());
    }

    /**
     * Store a newly created school in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'icon' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'country_id' => 'required|exists:countries,id',
            'governorate_id' => 'required|exists:governorates,id',
            'delegation_id' => 'required|exists:delegations,id',
        ]);
        $school = new School;
        $school->name = request('name');
        $school->type = request('type');
        $school->address = request('address');
        $school->country_id = request('country_id');
        $school->governorate_id = request('governorate_id');
        $school->delegation_id = request('delegation_id');


        if (request('icon')) {
            $iconName = time() . '.' . request('icon')->getClientOriginalExtension();
            request('icon')->move(public_path('uploads/school/'), $iconName);
            $school->icon = $iconName;
        }

        $school->save();


        return new SchoolResource($school);
    }

    /**
     * Display the specified school.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new SchoolResource(School::findOrFail($id));
    }

    /**
     * Update the specified school in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'icon' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'country_id' => 'required|exists:countries,id',
            'governorate_id' => 'required|exists:governorates,id',
            'delegation_id' => 'required|exists:delegations,id',

        ]);

        $school->name = request('name');
        $school->type = request('type');
        $school->address = request('address');
        $school->country_id = request('country_id');
        $school->governorate_id = request('governorate_id');
        $school->delegation_id = request('delegation_id');

        if (request('icon')) {
            $iconName = time() . '.' . request('icon')->getClientOriginalExtension();
            request('icon')->move(public_path('uploads/school/'), $iconName);
            $school->icon = $iconName;
        }

        $school->save();

        return new SchoolResource($school);

    }

    /**
     * Remove the specified school from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school = School::destroy($id);
        return response()->json([
            'message' => 'School deleted successfully'
        ], 200);
    }

    public function students(){
        $students = User::where('school_id', auth()->user()->school_id)->get();
        // $students = User::all();
        return UserResource::collection($students);
    }
}