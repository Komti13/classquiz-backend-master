<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ChapterType;
use App\Http\Resources\ChapterType as ChapterTypeResource;

/**
 * @resource ChapterTypes
 *
 */
class ChapterTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:EDITOR')->only('store', 'update', 'destroy');
    }

    /**
     * Display a listing of the chapterType.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ChapterTypeResource::collection(ChapterType::all());
    }

    /**
     * Store a newly created chapterType in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);
        $chapterType = new ChapterType;
        $chapterType->name = request('name');
        $chapterType->order = request('order');
        $chapterType->save();

        return new ChapterTypeResource($chapterType);
    }

    /**
     * Display the specified chapterType.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ChapterTypeResource(ChapterType::findOrFail($id));
    }

    /**
     * Update the specified chapterType in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $chapterType = ChapterType::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);
        $chapterType->name = request('name');
        $chapterType->order = request('order');
        $chapterType->save();

        return new ChapterResource($chapterType);

    }

    /**
     * Remove the specified chapter from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chapterType = Chapter::destroy($id);
        return response()->json([
            'message' => 'Chapter Type deleted successfully'
        ], 200);
    }

}
