<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Quizo;
use App\Http\Resources\Quizo as QuizoResource;
use App\Http\Resources\QuizoQuizoItem as QuizoQuizoItemResource;

/**
 * @resource Quizo
 *
 */
class QuizoController extends Controller
{
    /**
     * Display a listing of the quizo.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuizoResource::collection(Quizo::all());
    }

    /**
     * Store a newly created quizo in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return QuizoResource
     */
    public function store(Request $request)
    {
        request()->validate([
            'user_id' => 'required|integer|exists:users,id',
            'level' => 'required|integer',
            'progress' => 'required|numeric',
            'selected_item' => 'required|string|max:255',
        ]);
        $quizo = new Quizo;
        $quizo->user_id = request('user_id');
        $quizo->level = request('level');
        $quizo->progress = request('progress');
        $quizo->selected_item = request('selected_item');

        $quizo->save();


        return new QuizoResource($quizo);
    }

    /**
     * Display the specified quizo.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new QuizoResource(Quizo::findOrFail($id));
    }

    /**
     * Update the specified quizo in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quizo = Quizo::findOrFail($id);
        request()->validate([
            'user_id' => 'required|integer|exists:users,id',
            'level' => 'required|integer',
            'progress' => 'required|numeric',
            'selected_item' => 'required|string|max:255',
        ]);
        $quizo->user_id = request('user_id');
        $quizo->level = request('level');
        $quizo->progress = request('progress');
        $quizo->selected_item = request('selected_item');

        $quizo->save();


        return new QuizoResource($quizo);

    }

    /**
     * Remove the specified quizo from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quizo = Quizo::destroy($id);
        return response()->json([
            'message' => 'Quizo deleted successfully'
        ], 200);
    }

    /**
     * Display a listing of quizo ByQuizoItem id.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getByQuizoItem($quizoItemId)
    {
        $quizos = Quizo::whereHas('quizoItems', function ($q) use ($quizoItemId) {
            $q->where('quizo_item_id', $quizoItemId);
        })->get();

        return QuizoResource::collection($quizos);
    }

    /**
     * addQuizoItemToQuizo
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addQuizoItemToQuizo()
    {
        request()->validate([
            'quizo_id' => 'required|exists:quizos,id',
            'quizo_item_id' => 'required|exists:quizo_items,id',
        ]);

        $quizo = Quizo::findOrFail(request('quizo_id'));
        $quizo->quizoItems()->sync(request('quizo_item_id'), false);

        return response()->json([
            'message' => 'Quizo added to quizo_item successfully'
        ], 200);
    }


    /**
     * removeQuizoItemFromQuizo
     * @authenticated
     * @param  [int] userId
     * @param  [int] rewardId
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeQuizoItemFromQuizo($quizoId, $quizoItemId)
    {
        $quizo = Quizo::findOrFail($quizoId);
        $quizo->quizoItems()->detach($quizoItemId);
        return response()->json([
            'message' => 'Quizo_item deleted quizo from successfully'
        ], 200);
    }

    /**
     * Display a listing of quizo By user id.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getByUser($userId)
    {
        $quizos = Quizo::where('user_id', $userId)->get();

        return QuizoResource::collection($quizos);
    }
}
