<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Country;
use App\Http\Resources\Country as CountryResource;

/**
 * @resource Countries
 *
 */
class CountryController extends Controller
{
    /**
     * Display a listing of the country.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CountryResource::collection(Country::all());
    }

    /**
     * Store a newly created country in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
        ]);
        $country = new Country;
        $country->name = request('name');

        $country->save();


        return new CountryResource($country);
    }

    /**
     * Display the specified country.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new CountryResource(Country::findOrFail($id));
    }

    /**
     * Update the specified country in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
        ]);
        $country->name = request('name');

        $country->save();

        return new CountryResource($country);

    }

    /**
     * Remove the specified country from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::destroy($id);
        return response()->json([
            'message' => 'Country deleted successfully'
        ], 200);
    }
}
