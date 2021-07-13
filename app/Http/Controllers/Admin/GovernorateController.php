<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Governorate;
use App\Country;

class GovernorateController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $governorates = Governorate::query()->with('country');
            return datatables()->eloquent($governorates)->toJson();
        }

        return view('admin.governorate.index');
    }

    public function create()
    {
        $countries = Country::pluck('name', 'id');
        return view('admin.governorate.create', compact('countries'));
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);
        $governorate = new Governorate;
        $governorate->name = request('name');
        $governorate->country_id = request('country_id');

        $governorate->save();


        return redirect()->route('governorates.index')
            ->with('success', 'Governorate created successfully');
    }

    public function edit($id)
    {

        $governorate = Governorate::findOrFail($id);
        $countries = Country::pluck('name', 'id');
        return view('admin.governorate.edit', compact('governorate', 'countries'));
    }


    public function update($id)
    {
        $governorate = Governorate::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
        ]);
        $governorate->name = request('name');
        $governorate->country_id = request('country_id');

        $governorate->save();
        return redirect()->route('governorates.index')
            ->with('success', 'Governorate edited successfully');
    }


    public function destroy($id)
    {
        Governorate::destroy($id);
        session()->flash('success', 'Governorate deleted successfully');

        return response()->json(['success' => true]);
    }
}
