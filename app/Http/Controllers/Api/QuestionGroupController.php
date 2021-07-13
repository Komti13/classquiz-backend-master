<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuestionGroup;
use App\Chapter;
use App\Http\Resources\QuestionGroup as QuestionGroupResource;

/**
 * @resource QuestionGroups
 *
 */
class QuestionGroupController extends Controller
{
    /**
     * Display a listing of the questionGroup.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuestionGroupResource::collection(QuestionGroup::all());
    }

    /**
     * Display a listing of the questionGroup by chapterId.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listQuestionGroupsByChapter($chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);
        return QuestionGroupResource::collection(QuestionGroup::where('chapter_id', $chapter->id)->get());
    }

    /**
     * Store a newly created questionGroup in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'string|max:255',
            'index' => 'integer',
            'optional' => 'boolean',
            'chapter_id' => 'required|integer|exists:chapters,id',

        ]);
        $questionGroup = new QuestionGroup;
        $questionGroup->name = request('name');
        $questionGroup->index = request('index');
        $questionGroup->optional = request('optional') ?? false;
        $questionGroup->chapter_id = request('chapter_id');

        $questionGroup->save();

        return new QuestionGroupResource($questionGroup);
    }

    /**
     * Display the specified questionGroup.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new QuestionGroupResource(QuestionGroup::findOrFail($id));
    }

    /**
     * Update the specified questionGroup in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $questionGroup = QuestionGroup::findOrFail($id);
        request()->validate([
            'name' => 'string|max:255',
            'index' => 'integer',
            'optional' => 'boolean',
            'chapter_id' => 'required|integer|exists:chapters,id',

        ]);

        $questionGroup->name = request('name');
        $questionGroup->index = request('index');
        $questionGroup->optional = request('optional') ?? false;
        $questionGroup->chapter_id = request('chapter_id');

        $questionGroup->save();

        return new QuestionGroupResource($questionGroup);

    }

    /**
     * Remove the specified questionGroup from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $questionGroup = QuestionGroup::destroy($id);
        return response()->json([
            'message' => 'Question Group deleted successfully'
        ], 200);
    }
}
