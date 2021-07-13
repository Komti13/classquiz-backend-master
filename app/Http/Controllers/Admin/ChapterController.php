<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Chapter;
use App\Subject;
use App\Level;
use App\ChapterType;

class ChapterController extends Controller
{

    public function index()
    {
        $levels = Level::all();
        if (request()->isXmlHttpRequest()) {
            $chapters = Chapter::query()
                ->with('chapterType', 'subject', 'levels')
            ->when(request('level'), function ($q){
                $q->whereHas('levels', function ($q){
                    $q->where('id', request('level'));
                });
            });
            return datatables()->eloquent($chapters)->toJson();
        }

        return view('admin.chapter.index', compact( 'levels'));
    }

    public function create()
    {
        $subjects = Subject::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        $chapterTypes = ChapterType::pluck('name', 'id');
        return view('admin.chapter.create', compact('subjects', 'levels', 'chapterTypes'));
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'order' => 'required|integer',
            'time' => 'nullable|string|max:255',
            'related' => 'required|boolean',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_type_id' => 'required|integer|exists:chapter_types,id',
            'levels.*' => 'required|integer|exists:levels,id',
        ]);
        $chapter = new Chapter;
        $chapter->name = request('name');
        $chapter->short_name = request('short_name');
        $chapter->description = request('description');
        // $chapter->order = request('order');
        $chapter->time = request('time');
        $chapter->related = request('related');
        $chapter->subject_id = request('subject_id');
        $chapter->chapter_type_id = request('chapter_type_id');

        $iconName = time() . '.' . request('icon')->getClientOriginalExtension();
        request('icon')->move(public_path('uploads/chapter/'), $iconName);
        $chapter->icon = $iconName;

        $chapter->save();

        $chapter->levels()->sync(request('levels'));


        return redirect()->route('chapters.index')
            ->with('success', 'Chapter created successfully');
    }

    public function edit($id)
    {

        $chapter = Chapter::findOrFail($id);
        $subjects = Subject::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        $chapterTypes = ChapterType::pluck('name', 'id');
        return view('admin.chapter.edit', compact('chapter', 'subjects', 'levels', 'chapterTypes'));
    }


    public function update($id)
    {
        $chapter = Chapter::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'icon' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'orders.*' => 'required|integer',
            'time' => 'nullable|string|max:255',
            'related' => 'boolean',
            'subject_id' => 'required|exists:subjects,id',
            'chapter_type_id' => 'required|integer|exists:chapter_types,id',
            'levels.*' => 'required|integer|exists:levels,id',
        ]);
        $chapter->name = request('name');
        $chapter->short_name = request('short_name');
        $chapter->description = request('description');
        // $chapter->order = request('order');
        $chapter->time = request('time');
        $chapter->related = request('related');
        $chapter->subject_id = request('subject_id');
        $chapter->chapter_type_id = request('chapter_type_id');

        $levels = [];
        foreach (request('orders') as $level_id => $order) {
            $levels[$level_id] = ['order' => $order];
        }
        $chapter->levels()->sync($levels);

        if (request('icon')) {
            $iconName = time() . '.' . request('icon')->getClientOriginalExtension();
            request('icon')->move(public_path('uploads/chapter/'), $iconName);
            $chapter->icon = $iconName;
        }

        $chapter->save();
        return redirect()->route('chapters.index')
            ->with('success', 'Chapter edited successfully');
    }


    public function destroy($id)
    {
        Chapter::destroy($id);
        session()->flash('success', 'Chapter deleted successfully');

        return response()->json(['success' => true]);
    }
}
