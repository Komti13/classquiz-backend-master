<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Badge;
use App\Http\Resources\Badge as BadgeResource;

/**
 * @resource Badges
 *
 */
class BadgeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');

        // $this->middleware('role:EDITOR')->except('listByUser');

        // $this->middleware('role:STUDENT')->only('listByUser'); 
    }

    /**
     * Display a listing of the badge.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BadgeResource::collection(Badge::all());
    }

    /**
     * Store a newly created badge in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'type' => 'required|string|max:255',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        $badge = new Badge;
        $badge->type = request('type');
        $badge->chapter_id = request('chapter_id');
        $badge->user_id = request('user_id');

        $badge->save();


        return new BadgeResource($badge);
    }

    /**
     * Display the specified badge.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new BadgeResource(Badge::findOrFail($id));
    }

    /**
     * Update the specified badge in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);
        request()->validate([
            'type' => 'required|string|max:255',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'user_id' => 'required|integer|exists:users,id',

        ]);

        $badge->type = request('type');
        $badge->chapter_id = request('chapter_id');
        $badge->user_id = request('user_id');


        $badge->save();

        return new BadgeResource($badge);

    }

    /**
     * Remove the specified badge from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $badge = Badge::destroy($id);
        return response()->json([
            'message' => 'Badge deleted successfully'
        ], 200);
    }

    /**
     * Display a listing of the badge.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listByUser($userId)
    {
        $badges = Badge::
        where('user_id', $userId)
            // ->where(function ($query) {
            //     $query->groupBy('chapter_id');
            // })
            ->get();
        return response()->json($badges);
    }
}
