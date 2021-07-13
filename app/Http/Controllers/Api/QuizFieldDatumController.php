<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\QuizFieldDatum;
use App\Http\Resources\QuizFieldDatum as QuizFieldDatumResource;

/**
 * @resource QuizFieldData
 *
 */
class QuizFieldDatumController extends Controller
{
    /**
     * Display a listing of the quizFieldDatum.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuizFieldDatumResource::collection(QuizFieldDatum::all());
    }

    /**
     * Store a newly created quizFieldDatum in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'field_type' => 'required|string|max:255',
            'block_a_x' => 'nullable|numeric',
            'block_a_y' => 'nullable|numeric',
            'block_b_x' => 'nullable|numeric',
            'block_b_y' => 'nullable|numeric',
            'text_a' => 'nullable|string|max:2000',
            'text_b' => 'nullable|string|max:2000',
            'sprite_a' => 'nullable|string|max:255',
            'sprite_b' => 'nullable|string|max:255',
            'is_first_field' => 'required|boolean',
            'is_last_field' => 'required|boolean',
            'is_active' => 'required|boolean',
            'toggle_value' => 'required|boolean',
            'field_index' => 'nullable|integer',
            'group_id' => 'nullable|string|max:255',
            'block_a_value' => 'nullable|string|max:255',
            'block_b_value' => 'nullable|string|max:255',
            'is_child' => 'nullable|boolean',
            'is_parent' => 'nullable|boolean',
            'parent_id' => 'nullable|string',
            'question_id' => 'required|integer|exists:questions,id',
        ]);
        $quizFieldDatum = new QuizFieldDatum;
        $quizFieldDatum->field_type = request('field_type');
        $quizFieldDatum->block_a_x = request('block_a_x');
        $quizFieldDatum->block_a_y = request('block_a_y');
        $quizFieldDatum->block_b_x = request('block_b_x');
        $quizFieldDatum->block_b_y = request('block_b_y');
        $quizFieldDatum->text_a = request('text_a');
        $quizFieldDatum->text_b = request('text_b');
        $quizFieldDatum->sprite_a = request('sprite_a');
        $quizFieldDatum->sprite_b = request('sprite_b');
        $quizFieldDatum->is_first_field = request('is_first_field');
        $quizFieldDatum->is_last_field = request('is_last_field');
        $quizFieldDatum->is_active = request('is_active');
        $quizFieldDatum->toggle_value = request('toggle_value');
        $quizFieldDatum->field_index = request('field_index');
        $quizFieldDatum->group_id = request('group_id');
        $quizFieldDatum->block_a_value = request('block_a_value');
        $quizFieldDatum->block_b_value = request('block_b_value');
        $quizFieldDatum->is_child = request('is_child');
        $quizFieldDatum->is_parent = request('is_parent');
        $quizFieldDatum->parent_id = request('parent_id');
        $quizFieldDatum->question_id = request('question_id');

        $quizFieldDatum->save();

        return new QuizFieldDatumResource($quizFieldDatum);
    }

    /**
     * Store a batch of quizFieldDatum in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeBatch(Request $request)
    {
        request()->validate([
            '*.field_type' => 'required|string|max:255',
            '*.block_a_x' => 'nullable|numeric',
            '*.block_a_y' => 'nullable|numeric',
            '*.block_b_x' => 'nullable|numeric',
            '*.block_b_y' => 'nullable|numeric',
            '*.text_a' => 'nullable|string|max:2000',
            '*.text_b' => 'nullable|string|max:2000',
            '*.sprite_a' => 'nullable|string|max:255',
            '*.sprite_b' => 'nullable|string|max:255',
            '*.is_first_field' => 'required|boolean',
            '*.is_last_field' => 'required|boolean',
            '*.is_active' => 'required|boolean',
            '*.toggle_value' => 'required|boolean',
            '*.field_index' => 'nullable|integer',
            '*.group_id' => 'nullable|string|max:255',
            '*.block_a_value' => 'nullable|string|max:255',
            '*.block_b_value' => 'nullable|string|max:255',
            '*.is_child' => 'nullable|boolean',
            '*.is_parent' => 'nullable|boolean',
            '*.parent_id' => 'nullable|string',
            '*.question_id' => 'required|integer|exists:questions,id',
        ]);

        $fields = request()->all();

        $quizFieldData = [];

        foreach ($fields as $field) {

            $quizFieldDatum = QuizFieldDatum::create($field);

            $quizFieldData[] = $quizFieldDatum->id;
        }

        return QuizFieldDatumResource::collection(QuizFieldDatum::find($quizFieldData));
    }

    /**
     * Display the specified quizFieldDatum.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new QuizFieldDatumResource(QuizFieldDatum::findOrFail($id));
    }

    /**
     * Update the specified quizFieldDatum in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quizFieldDatum = QuizFieldDatum::findOrFail($id);
        request()->validate([
            'field_type' => 'required|string|max:255',
            'block_a_x' => 'nullable|numeric',
            'block_a_y' => 'nullable|numeric',
            'block_b_x' => 'nullable|numeric',
            'block_b_y' => 'nullable|numeric',
            'text_a' => 'nullable|string|max:2000',
            'text_b' => 'nullable|string|max:2000',
            'sprite_a' => 'nullable|string|max:255',
            'sprite_b' => 'nullable|string|max:255',
            'is_first_field' => 'required|boolean',
            'is_last_field' => 'required|boolean',
            'is_active' => 'required|boolean',
            'toggle_value' => 'required|boolean',
            'field_index' => 'nullable|integer',
            'group_id' => 'nullable|string|max:255',
            'block_a_value' => 'nullable|string|max:255',
            'block_b_value' => 'nullable|string|max:255',
            'is_child' => 'nullable|boolean',
            'is_parent' => 'nullable|boolean',
            'parent_id' => 'nullable|string',
            'question_id' => 'required|integer|exists:questions,id',

        ]);

        $quizFieldDatum->field_type = request('field_type');
        $quizFieldDatum->block_a_x = request('block_a_x');
        $quizFieldDatum->block_a_y = request('block_a_y');
        $quizFieldDatum->block_b_x = request('block_b_x');
        $quizFieldDatum->block_b_y = request('block_b_y');
        $quizFieldDatum->text_a = request('text_a');
        $quizFieldDatum->text_b = request('text_b');
        $quizFieldDatum->sprite_a = request('sprite_a');
        $quizFieldDatum->sprite_b = request('sprite_b');
        $quizFieldDatum->is_first_field = request('is_first_field');
        $quizFieldDatum->is_last_field = request('is_last_field');
        $quizFieldDatum->is_active = request('is_active');
        $quizFieldDatum->toggle_value = request('toggle_value');
        $quizFieldDatum->field_index = request('field_index');
        $quizFieldDatum->group_id = request('group_id');
        $quizFieldDatum->block_a_value = request('block_a_value');
        $quizFieldDatum->block_b_value = request('block_b_value');
        $quizFieldDatum->is_child = request('is_child');
        $quizFieldDatum->is_parent = request('is_parent');
        $quizFieldDatum->parent_id = request('parent_id');
        $quizFieldDatum->question_id = request('question_id');

        $quizFieldDatum->save();

        return new QuizFieldDatumResource($quizFieldDatum);

    }

    /**
     * Update a batch of quizFieldDatum in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateBatch(Request $request)
    {
        request()->validate([
            '*.id' => 'required',
            '*.field_type' => 'required|string|max:255',
            '*.block_a_x' => 'nullable|numeric',
            '*.block_a_y' => 'nullable|numeric',
            '*.block_b_x' => 'nullable|numeric',
            '*.block_b_y' => 'nullable|numeric',
            '*.text_a' => 'nullable|string|max:2000',
            '*.text_b' => 'nullable|string|max:2000',
            '*.sprite_a' => 'nullable|string|max:255',
            '*.sprite_b' => 'nullable|string|max:255',
            '*.is_first_field' => 'required|boolean',
            '*.is_last_field' => 'required|boolean',
            '*.is_active' => 'required|boolean',
            '*.toggle_value' => 'required|boolean',
            '*.field_index' => 'nullable|integer',
            '*.group_id' => 'nullable|string|max:255',
            '*.block_a_value' => 'nullable|string|max:255',
            '*.block_b_value' => 'nullable|string|max:255',
            '*.is_child' => 'nullable|boolean',
            '*.is_parent' => 'nullable|boolean',
            '*.parent_id' => 'nullable|string',
            '*.question_id' => 'required|integer|exists:questions,id',
        ]);

        $fields = request()->all();

        $quizFieldData = [];

        foreach ($fields as $field) {

            $quizFieldDatum = QuizFieldDatum::find($field["id"])->update($field);

            $quizFieldData[] = $field["id"];

        }

        return QuizFieldDatumResource::collection(QuizFieldDatum::find($quizFieldData));
    }

    /**
     * Remove the specified quizFieldDatum from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quizFieldDatum = QuizFieldDatum::destroy($id);
        return response()->json([
            'message' => 'QuizFieldDatum deleted successfully'
        ], 200);
    }

    /**
     * Remove all quizFieldData by question id
     * @authenticated
     * @param int $questionId
     * @return \Illuminate\Http\Response
     */
    public function destroyByQuestion($questionId)
    {
        $deleted = QuizFieldDatum::where('question_id', $questionId)->delete();
        return response()->json([
            'message' => $deleted . ' QuizFieldDatum deleted'
        ], 200);
    }

    /**
     * Remove list of quizFieldData
     * @authenticated
     * @param array
     * @return \Illuminate\Http\Response
     */
    public function destroyBatch()
    {
        $fields = request()->all();
        $deleted = QuizFieldDatum::destroy($fields);
        return response()->json([
            'message' => $deleted . ' QuizFieldDatum deleted'
        ], 200);
    }

    public function listByChapter($chapterId)
    {
        $quizFieldData = QuizFieldDatum::whereHas('question', function ($q) use ($chapterId) {
            $q->where('chapter_id', $chapterId);
        })->get();
        return QuizFieldDatumResource::collection($quizFieldData);
    }

}
