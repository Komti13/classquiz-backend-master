<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Country;

class CountryController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $countries = Country::query();
            return datatables()->eloquent($countries)->toJson();
        }

        return view('admin.country.index');
    }

    public function create()
    {
        return view('admin.country.create');
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
        ]);
        $country = new Country;
        $country->name = request('name');

        $country->save();


        return redirect()->route('countries.index')
            ->with('success', 'Country created successfully');
    }

    public function edit($id)
    {

        $country = Country::findOrFail($id);
        return view('admin.country.edit', compact('country'));
    }


    public function update($id)
    {
        $country = Country::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
        ]);
        $country->name = request('name');

        $country->save();
        return redirect()->route('countries.index')
            ->with('success', 'Country edited successfully');
    }


    public function destroy($id)
    {
        Country::destroy($id);
        session()->flash('success', 'Country deleted successfully');

        return response()->json(['success' => true]);
    }
}
