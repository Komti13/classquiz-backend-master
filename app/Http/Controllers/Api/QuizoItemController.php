<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuizoItem;
use App\Http\Resources\QuizoItem as QuizoItemResource;

/**
 * @resource QuizoItem
 *
 */
class QuizoItemController extends Controller
{
    /**
     * Display a listing of the quizoItem.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuizoItemResource::collection(QuizoItem::all());
    }

    /**
     * Store a newly created quizoItem in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'item_id' => 'required|integer',
            'category' => 'required|integer',
            'name' => 'required|string|max:255'
        ]);
        $quizoItem = new QuizoItem;
        $quizoItem->user_id = request('item_id');
        $quizoItem->level = request('category');
        $quizoItem->progress = request('name');

        $quizoItem->save();


        return new QuizoItemResource($quizoItem);
    }

    /**
     * Display the specified quizoItem.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new QuizoItemResource(QuizoItem::findOrFail($id));
    }

    /**
     * Update the specified quizoItem in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quizoItem = QuizoItem::findOrFail($id);
        request()->validate([
            'item_id' => 'required|integer',
            'category' => 'required|integer',
            'name' => 'required|string|max:255'
        ]);
        $quizoItem->user_id = request('item_id');
        $quizoItem->level = request('category');
        $quizoItem->progress = request('name');

        $quizoItem->save();


        return new QuizoItemResource($quizoItem);

    }

    /**
     * Remove the specified quizoItem from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quizoItem = QuizoItem::destroy($id);
        return response()->json([
            'message' => 'QuizoItem deleted successfully'
        ], 200);
    }

    /**
     * Display a listing of quizoItem by quizo id.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getByQuizo($quizoId)
    {
        $quizoItems = QuizoItem::whereHas('quizos', function ($q) use ($quizoId) {
            $q->where('quizo_id', $quizoId);
        })->get();

        return QuizoItemResource::collection($quizoItems);
    }
}
