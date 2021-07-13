<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Word;
use App\Http\Resources\Word as WordResource;

/**
 * @resource Words
 *
 */
class WordController extends Controller
{
    /**
     * Display a listing of the word.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return WordResource::collection(Word::all());
    }

    /**
     * Store a newly created word in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'question_id' => 'required|integer|exists:questions,id',

        ]);
        $word = new Word;
        $word->name = request('name');
        $word->question_id = request('question_id');

        $word->save();

        return new WordResource($word);
    }

    /**
     * Display the specified word.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new WordResource(Word::findOrFail($id));
    }

    /**
     * Update the specified word in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $word = Word::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'question_id' => 'required|integer|exists:questions,id',

        ]);

        $word->name = request('name');
        $word->question_id = request('question_id');

        $word->save();

        return new WordResource($word);

    }

    /**
     * Remove the specified word from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $word = Word::destroy($id);
        return response()->json([
            'message' => 'Word deleted successfully'
        ], 200);
    }
}
