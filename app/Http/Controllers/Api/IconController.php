<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Icon;
use App\Chapter;
use App\Question;
use App\QuizFieldDatum;
use App\Http\Resources\Icon as IconResource;

/**
 * @resource Icons
 *
 */
class IconController extends Controller
{
    /**
     * Display a listing of the icon.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IconResource::collection(Icon::all());
    }

    /**
     * Display a listing of the icon by levelId.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listByChapter($chapterId)
    {
        $chapter = Chapter::findOrFail($chapterId);
        $questions = Question::where('chapter_id', $chapter->id)->pluck('id');
        $quizFieldDataA = QuizFieldDatum::whereIn('question_id', $questions)->where('sprite_a', '<>', 'NULL')->pluck('sprite_a');
        $quizFieldDataB = QuizFieldDatum::whereIn('question_id', $questions)->where('sprite_b', '<>', 'NULL')->pluck('sprite_b');
        $icons = Icon::whereIn('id', $quizFieldDataA)->orWhereIn('id', $quizFieldDataB)->get();

        return IconResource::collection($icons);
    }

    /**
     * Display a listing of the icon for the specified ids array.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function listByIds()
    {
        return IconResource::collection(Icon::find(request()->all()));
    }

    public function listByChapterQuestions($chapterId)
    {
        $iconIds = Question::whereHas('chapter', function ($q) use ($chapterId) {
            $q->where('chapter_id', $chapterId);
        })
            ->where('bg_color', '!=', 'NULL')
            ->whereNotNull('bg_color')
            ->pluck('bg_color');
        $icons = Icon::whereIn('id', $iconIds)->get();

        return IconResource::collection($icons);
    }

    /**
     * Store a newly created icon in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|integer',
            'is_default' => 'required|boolean',
            'atlas_id' => 'required|exists:atlases,id',
            'direct_url' => 'nullable',
            'index' => 'required|integer'
        ]);
        $icon = new Icon;
        $icon->name = request('name');
        $icon->category = request('category');
        $icon->is_default = request('is_default') ? 1 : 0;
        $icon->atlas_id = request('atlas_id');

        if (isset(request()->file()['direct_url'])) {
            request()->validate(['direct_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048']);
            $directUrlName = time() . '.' . request('direct_url')->getClientOriginalExtension();
            request('direct_url')->move(public_path('uploads/icon/direct/'), $directUrlName);
            $icon->direct_url = $directUrlName;
        } else {
            request()->validate(['direct_url' => 'nullable|string|max:255']);
            $icon->direct_url = request('direct_url');
        }
        $icon->index = request('index');


        $icon->save();


        return new IconResource($icon);
    }

    /**
     * Display the specified icon.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new IconResource(Icon::findOrFail($id));
    }

    /**
     * Update the specified icon in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $icon = Icon::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|integer',
            'is_default' => 'required|boolean',
            'atlas_id' => 'required|exists:atlases,id',
            'direct_url' => 'nullable',
            'index' => 'required|integer'
        ]);

        $icon->category = request('category');
        $icon->is_default = request('is_default') ? 1 : 0;
        $icon->atlas_id = request('atlas_id');

        if (isset(request()->file()['direct_url'])) {
            request()->validate(['direct_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048']);
            $directUrlName = time() . '.' . request('direct_url')->getClientOriginalExtension();
            request('direct_url')->move(public_path('uploads/icon/direct/'), $directUrlName);
            $icon->direct_url = $directUrlName;
        } else {
            request()->validate(['direct_url' => 'nullable|string|max:255']);
            $icon->direct_url = request('direct_url');
        }
        $icon->index = request('index');
        $icon->save();

        return new IconResource($icon);

    }

    /**
     * Remove the specified icon from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $icon = Icon::destroy($id);
        return response()->json([
            'message' => 'Icon deleted successfully'
        ], 200);
    }
}
