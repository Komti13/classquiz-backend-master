<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Chapter;
use App\Http\Resources\Chapter as ChapterResource;

/**
 * @resource Chapters
 *
 */
class ChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'listBySubject', 'listByLevel');
        $this->middleware('role:EDITOR')->only('store', 'update', 'destroy');
    }

    /**
     * Display a listing of the chapter.
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ChapterResource::collection(Chapter::all());
    }

    /**
     * Store a newly created chapter in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'time' => 'nullable|string|max:255',
            'subject_id' => 'required|integer|exists:subjects,id',
            'chapter_type_id' => 'required|integer|exists:chapter_types,id',
            'levels.*' => 'required|integer|exists:levels,id',
        ]);
        $chapter = new Chapter;
        $chapter->name = request('name');
        $chapter->short_name = request('short_name');
        $chapter->description = request('description');
        $chapter->order = request('order');
        $chapter->time = request('time');
        $chapter->subject_id = request('subject_id');
        $chapter->chapter_type_id = request('chapter_type_id');
        $chapter->levels()->sync(request('levels'));

        $iconName = time() . '.' . request('icon')->getClientOriginalExtension();
        request('icon')->move(public_path('uploads/chapter/'), $iconName);
        $chapter->icon = $iconName;

        $chapter->save();

        return new ChapterResource($chapter);
    }

    /**
     * Display the specified chapter.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ChapterResource(Chapter::findOrFail($id));
    }

    /**
     * Update the specified chapter in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'icon' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'time' => 'nullable|string|max:255',
            'subject_id' => 'required|integer|exists:subjects,id',
            'chapter_type_id' => 'required|integer|exists:chapter_types,id',
            'levels.*' => 'required|integer|exists:levels,id',
        ]);
        $chapter->name = request('name');
        $chapter->short_name = request('short_name');
        $chapter->description = request('description');
        $chapter->order = request('order');
        $chapter->time = request('time');
        $chapter->subject_id = request('subject_id');
        $chapter->chapter_type_id = request('chapter_type_id');
        $chapter->levels()->sync(request('levels'));

        if (request('icon')) {
            $iconName = time() . '.' . request('icon')->getClientOriginalExtension();
            request('icon')->move(public_path('uploads/chapter/'), $iconName);
            $chapter->icon = $iconName;
        }

        $chapter->save();

        return new ChapterResource($chapter);

    }

    /**
     * Remove the specified chapter from storage.
     * @authenticated
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chapter = Chapter::destroy($id);
        return response()->json([
            'message' => 'Chapter deleted successfully'
        ], 200);
    }

    public function listBySubject($subjectId, $levelId)
    {
        $chapters = Chapter::where('subject_id', $subjectId)
            ->whereHas('levels', function ($q) use ($levelId) {
                $q->where('id', $levelId);
            })->get();
        return ChapterResource::collection($chapters);
    }

    public function listByLevel($levelId)
    {
        $chapters = Chapter::whereHas('levels', function ($q) use ($levelId) {
            $q->where('id', $levelId);
        })
            ->get();
        $chapters->map(function ($chapter) use ($levelId) {
            $chapter->guestUnlocked = $chapter->unlocked($levelId);
            $chapter->guestIsFree = $chapter->isFree($levelId);
            $chapter->levelId = $levelId;
        });
        return ChapterResource::collection($chapters);
    }

}
