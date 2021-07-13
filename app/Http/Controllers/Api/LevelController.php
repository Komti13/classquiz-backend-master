<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Level;
use App\Http\Resources\Level as LevelResource;

/**
 * @resource Levels
 *
 */
class LevelController extends Controller
{
    /**
     * Display a listing of the level.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = Level::where('enabled', true)->get();
        return LevelResource::collection($levels);
    }

    /**
     * Store a newly created level in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer',
            'enabled' => 'required|boolean',
        ]);
        $level = new Level;
        $level->name = request('name');
        $level->number = request('number');
        $level->enabled = request('enabled');


        $level->save();


        return new LevelResource($level);
    }

    /**
     * Display the specified level.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $level = Level::where('id', $id)->where('enabled', true)->get();
        if (!$level) {
            abort(404);
        }

        return new LevelResource($level);
    }

    /**
     * Update the specified level in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $level = Level::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer',
            'enabled' => 'required|boolean',
        ]);
        $level->name = request('name');
        $level->number = request('number');
        $level->enabled = request('enabled');

        $level->save();


        return new LevelResource($level);

    }

    /**
     * Remove the specified level from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $level = Level::destroy($id);
        return response()->json([
            'message' => 'Level deleted successfully'
        ], 200);
    }

}
