<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Achivement;
use App\Http\Resources\Achivement as AchivementResource;

/**
 * @resource Achivements
 *
 */
class AchivementController extends Controller
{
    /**
     * Display a listing of the achivement.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AchivementResource::collection(Achivement::all());
    }

    /**
     * Store a newly created achivement in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'user_id' => 'required|integer|exists:users,id',
            'donuts' => 'required|integer',
            'candy' => 'required|integer',
            'nb_completed_chapter' => 'required|integer',
            'total_time' => 'required|string|max:255',
            'total_candies' => 'nullable|integer',
            'total_donuts' => 'nullable|integer',
        ]);
        $achivement = new Achivement;
        $achivement->user_id = request('user_id');
        $achivement->donuts = request('donuts');
        $achivement->candy = request('candy');
        $achivement->nb_completed_chapter = request('nb_completed_chapter');
        $achivement->total_time = request('total_time');
        $achivement->total_candies = request('total_candies');
        $achivement->total_donuts = request('total_donuts');

        $achivement->save();

        return new AchivementResource($achivement);
    }

    /**
     * Display the specified achivement.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new AchivementResource(Achivement::findOrFail($id));
    }

    /**
     * Update the specified achivement in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $achivement = Achivement::findOrFail($id);
        request()->validate([
            'user_id' => 'required|integer|exists:users,id',
            'donuts' => 'required|integer',
            'candy' => 'required|integer',
            'nb_completed_chapter' => 'required|integer',
            'total_time' => 'required|string|max:255',
            'total_candies' => 'nullable|integer',
            'total_donuts' => 'nullable|integer',
        ]);
        $achivement->user_id = request('user_id');
        $achivement->donuts = request('donuts');
        $achivement->candy = request('candy');
        $achivement->nb_completed_chapter = request('nb_completed_chapter');
        $achivement->total_time = request('total_time');
        $achivement->total_candies = request('total_candies');
        $achivement->total_donuts = request('total_donuts');

        $achivement->save();

        return new AchivementResource($achivement);

    }

    /**
     * Remove the specified achivement from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $achivement = Achivement::destroy($id);
        return response()->json([
            'message' => 'Achivement deleted successfully'
        ], 200);
    }

    /**
     * Display a listing of the achivement by user id.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listByUser($userId)
    {
        $achivements = Achivement::where('user_id', $userId)->get();
        return AchivementResource::collection($achivements);
    }
}
