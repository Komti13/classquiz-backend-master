<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Level;
use App\Subject;
use App\Chapter;
use App\Question;
use App\QuestionGroup;

class QuestionController extends Controller
{

    public function index()
    {
        return view('admin.question.index');
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);
        return view('admin.question.show', compact('question'));
    }

    public function destroy($id)
    {
        $country = Question::destroy($id);
        return redirect()->route('questions.index')
            ->with('success', 'Question deleted successfully');
    }

    public function getLevels()
    {
        return response()->json(['data' => Level::all()]);
    }

    public function getSubjects($levelId)
    {
        $subjects = Subject::whereHas('levels', function ($query) use ($levelId) {
            $query->where('level_id', $levelId);
        })->get();
        return response()->json(['data' => $subjects]);
    }

    public function getChapters($levelId, $subjectId)
    {
        $chapters = Chapter::where('subject_id', $subjectId)->whereHas('levels', function ($q) use($levelId){
            $q->where('id', $levelId);
        })->get();
        return response()->json(['data' => $chapters]);
    }

    public function getQuestionGroups($chapterId)
    {
        $questionGroups = QuestionGroup::where('chapter_id', $chapterId)->get();
        return response()->json(['data' => $questionGroups]);
    }

    public function getQuestions($questionGroupId)
    {
        $questions = Question::where('question_group_id', $questionGroupId)->get();
        return response()->json(['data' => $questions]);
    }
}
