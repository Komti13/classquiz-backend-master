<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Score;
use App\Level;
use App\Http\Resources\Score as ScoreResource;
use Illuminate\Support\Facades\Auth;
use DB;

/**
 * @resource Scores
 *
 */
class ScoreController extends Controller
{
    /**
     * Display a listing of the score.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ScoreResource::collection(Score::all());
    }

    /**
     * Store a newly created score in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'score' => 'required|numeric|max:255',
            'time' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'question_group_id' => 'required|integer|exists:question_groups,id',
            'is_done' => 'nullable|boolean',
            'nb_mistakes' => 'nullable|integer',
        ]);
        $score = new Score;
        $score->score = request('score');
        $score->time = request('time');
        $score->user_id = request('user_id');
        $score->question_group_id = request('question_group_id');
        $score->is_done = request('is_done');
        $score->nb_mistakes = request('nb_mistakes');

        $score->save();


        return new ScoreResource($score);
    }

    /**
     * Store a batch of scores in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeBatch(Request $request)
    {
        request()->validate([
            '*.score' => 'required|numeric|max:255',
            '*.time' => 'required|string|max:255',
            '*.user_id' => 'required|integer|exists:users,id',
            '*.question_group_id' => 'required|integer|exists:question_groups,id',
            '*.is_done' => 'nullable|boolean',
            '*.nb_mistakes' => 'nullable|integer',
        ]);

        $scoresData = request()->all();

        $scores = [];

        foreach ($scoresData as $scoreData) {

            $score = Score::create($scoreData);

            $scores[] = $score->id;
        }

        return ScoreResource::collection(Score::find($scores));
    }

    /**
     * Display the specified score.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ScoreResource(Score::findOrFail($id));
    }

    /**
     * Update the specified score in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $score = Score::findOrFail($id);
        request()->validate([
            'score' => 'required|numeric|max:255',
            'time' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'question_group_id' => 'required|integer|exists:question_groups,id',
            'is_done' => 'nullable|boolean',
            'nb_mistakes' => 'nullable|integer',
        ]);

        $score->score = request('score');
        $score->time = request('time');
        $score->user_id = request('user_id');
        $score->question_group_id = request('question_group_id');
        $score->is_done = request('is_done');
        $score->nb_mistakes = request('nb_mistakes');

        $score->save();

        return new ScoreResource($score);

    }

    /**
     * Remove the specified score from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $score = Score::destroy($id);
        return response()->json([
            'message' => 'Score deleted successfully'
        ], 200);
    }

    public function userScoreByChapter($chapterId)
    {
        $user = Auth::guard('api')->user();
        $scores = Score::where('user_id', $user->id)->whereHas('questionGroup', function ($q) use ($chapterId) {
            $q->where('chapter_id', $chapterId);
        })->get();
        return ScoreResource::collection($scores);
    }

    public function userChapterProgress()
    {
        $user = Auth::guard('api')->user();
        $scores = [];
        foreach ($user->level->chapters as $chapter) {
            $scores[] = [
                'user_id' => $user->id,
                'chapter_id' => $chapter->id,
                'progress' => $chapter->userProgress()
            ];
        }
        return response()->json(['data' => $scores]);
    }
}
