<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ChapterType;

class ChapterTypeController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $chapterTypes = ChapterType::query();
            return datatables()->eloquent($chapterTypes)->toJson();
        }

        return view('admin.chapter_type.index');
    }

    public function create()
    {
        return view('admin.chapter_type.create');
    }

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

        return redirect()->route('chapter-types.index')
            ->with('success', 'Chapter type created successfully');
    }

    public function edit($id)
    {
        $chapterType = ChapterType::findOrFail($id);
        return view('admin.chapter_type.edit', compact('chapterType'));
    }

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

        return redirect()->route('chapter-types.index')
            ->with('success', 'Chapter type edited successfully');

    }

    public function destroy($id)
    {
        ChapterType::destroy($id);
        session()->flash('success', 'Chapter type deleted successfully');

        return response()->json(['success' => true]);
    }

}
