<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\School;
use App\Country;
use App\Governorate;
use App\Delegation;

class SchoolController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $schools = School::query();
            return datatables()->eloquent($schools)->toJson();
        }
        return view('admin.school.index');
    }

    public function create()
    {
        $countries = Country::pluck('name', 'id');
        $governorates = Governorate::pluck('name', 'id');
        $delegations = Delegation::pluck('name', 'id');
        return view('admin.school.create', compact('countries', 'governorates', 'delegations'));
    }

    public function store()
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


        return redirect()->route('schools.index')
            ->with('success', 'School created successfully');
    }

    public function edit($id)
    {
        $school = School::findOrFail($id);
        $countries = Country::pluck('name', 'id');
        $governorates = Governorate::pluck('name', 'id');
        $delegations = Delegation::pluck('name', 'id');
        return view('admin.school.edit', compact('school', 'countries', 'governorates', 'delegations'));
    }


    public function update($id)
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
        return redirect()->route('schools.index')
            ->with('success', 'School edited successfully');
    }


    public function destroy($id)
    {
        School::destroy($id);
        session()->flash('success', 'School deleted successfully');

        return response()->json(['success' => true]);
    }

    public function importExport()
    {
        return view('admin.school.importExport');
    }

    public function exportExcel($type)
    {
        $data = School::all(['name', 'address', 'type', 'country_id', 'governorate_id', 'delegation_id'])->toArray();
        return Excel::create('schools-' . time(), function ($excel) use ($data) {
            $excel->sheet('sheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download($type);
    }

    public function importExcel()
    {
        request()->validate([
            'import_file' => 'required'
        ]);

        $path = request()->file('import_file')->getRealPath();
        $data = Excel::load($path)->get();

        if ($data->count()) {
            foreach ($data as $key => $value) {

                $arr[] =
                    [
                        'name' => $value->name,
                        'address' => $value->address,
                        'type' => $value->type,
                        'country_id' => $value->country_id,
                        'governorate_id' => $value->governorate_id,
                        'delegation_id' => $value->delegation_id,
                    ];
            }
            if (!empty($arr)) {
                School::insert($arr);
            }
        }

        return back()->with('success', 'Insert Record successfully.');

    }

}
