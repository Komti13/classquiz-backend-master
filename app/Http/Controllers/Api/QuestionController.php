<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Question;
use App\Chapter;
use App\Http\Resources\Question as QuestionResource;

/**
 * @resource Questions
 *
 */
class QuestionController extends Controller
{
    /**
     * Display a listing of the question.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuestionResource::collection(Question::all());
    }

    /**
     * Display a listing of the question by chapterId.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listQuestionsByChapter($chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);
        return QuestionResource::collection(Question::where('chapter_id', $chapter->id)->get());
    }

    /**
     * Store a newly created question in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'nullable|string|max:255',
            'main_question' => 'nullable|string|max:255',
            'sub_question' => 'nullable|string|max:255',
            'score' => 'required|string|max:255',
            'is_confirmed' => 'required|boolean',
            'has_warning' => 'required|boolean',
            'generate_on_layout' => 'required|boolean',
            'is_new_question' => 'required|boolean',
            'situation' => 'nullable|string',
            'hints' => 'nullable|string|max:255',
            'bg_color' => 'nullable|string|max:255',
            'time' => 'nullable|integer',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'template_id' => 'nullable|string|exists:templates,id',
            'question_group_id' => 'nullable|integer|exists:question_groups,id',
            'index_in_group' => 'nullable|integer',
            'extension' => 'nullable|string'
        ]);
        $question = new Question;
        $question->title = request('title');
        $question->main_question = request('main_question');
        $question->sub_question = request('sub_question');
        $question->score = request('score');
        $question->is_confirmed = request('is_confirmed');
        $question->has_warning = request('has_warning');
        $question->generate_on_layout = request('generate_on_layout');
        $question->is_new_question = request('is_new_question');
        $question->situation = request('situation');
        $question->hints = request('hints');
        $question->bg_color = request('bg_color');
        $question->time = request('time');
        $question->chapter_id = request('chapter_id');
        $question->template_id = request('template_id');
        $question->index_in_group = request('index_in_group');
        $question->question_group_id = request('question_group_id');
        $question->extension = request('extension');

        $question->save();

        return new QuestionResource($question);
    }

    /**
     * Display the specified question.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new QuestionResource(Question::findOrFail($id));
    }

    /**
     * Update the specified question in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        request()->validate([
            'title' => 'nullable|string|max:255',
            'main_question' => 'nullable|string|max:255',
            'sub_question' => 'nullable|string|max:255',
            'score' => 'required|string|max:255',
            'is_confirmed' => 'required|boolean',
            'has_warning' => 'required|boolean',
            'generate_on_layout' => 'required|boolean',
            'is_new_question' => 'required|boolean',
            'situation' => 'nullable|string',
            'hints' => 'nullable|string|max:255',
            'bg_color' => 'nullable|string|max:255',
            'time' => 'nullable|integer',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'template_id' => 'nullable|string|exists:templates,id',
            'question_group_id' => 'nullable|integer|exists:question_groups,id',
            'index_in_group' => 'nullable|integer',
            'extension' => 'nullable|string'
        ]);

        $question->title = request('title');
        $question->main_question = request('main_question');
        $question->sub_question = request('sub_question');
        $question->score = request('score');
        $question->is_confirmed = request('is_confirmed');
        $question->has_warning = request('has_warning');
        $question->generate_on_layout = request('generate_on_layout');
        $question->is_new_question = request('is_new_question');
        $question->situation = request('situation');
        $question->hints = request('hints');
        $question->bg_color = request('bg_color');
        $question->time = request('time');
        $question->chapter_id = request('chapter_id');
        $question->template_id = request('template_id');
        $question->index_in_group = request('index_in_group');
        $question->question_group_id = request('question_group_id');
        $question->extension = request('extension');

        $question->save();

        return new QuestionResource($question);

    }

    /**
     * Remove the specified question from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::destroy($id);
        return response()->json([
            'message' => 'Question deleted successfully'
        ], 200);
    }

    /**
     * Remove all questions by question group id
     * @authenticated
     * @param int $groupId
     * @return \Illuminate\Http\Response
     */
    public function destroyByGroup($groupId)
    {
        $deleted = Question::where('question_group_id', $groupId)->delete();
        return response()->json([
            'message' => $deleted . ' Question deleted'
        ], 200);
    }
}
