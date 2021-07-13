<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Subject;
use App\Level;

class SubjectController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $subjects = Subject::query();
            return datatables()->eloquent($subjects)->toJson();
        }

        return view('admin.subject.index');
    }

    public function create()
    {
        $levels = Level::pluck('name', 'id');
        return view('admin.subject.create', compact('levels'));
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'levels' => 'nullable|array',
        ]);
        $subject = new Subject;
        $subject->name = request('name');
        $subject->order = request('order');

        $imageName = time() . '.' . request('image')->getClientOriginalExtension();
        request('image')->move(public_path('uploads/subject/'), $imageName);
        $subject->image = $imageName;

        $subject->save();

        $subject->levels()->attach(request('levels'));


        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully');
    }

    public function edit($id)
    {

        $subject = Subject::findOrFail($id);
        $levels = Level::pluck('name', 'id');
        $levels_selected = $subject->levels()->pluck('level_id');
        return view('admin.subject.edit', compact('subject', 'levels', 'levels_selected'));
    }


    public function update($id)
    {
        $subject = Subject::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'required|integer',
            'levels' => 'nullable|array',
        ]);
        $subject->name = request('name');
        $subject->order = request('order');

        if (request('image')) {
            $imageName = time() . '.' . request('image')->getClientOriginalExtension();
            request('image')->move(public_path('uploads/subject/'), $imageName);
            $subject->image = $imageName;
        }

        $subject->save();

        $subject->levels()->sync(request('levels'));

        return redirect()->route('subjects.index')
            ->with('success', 'Subject edited successfully');
    }


    public function destroy($id)
    {
        Subject::destroy($id);
        session()->flash('success', 'Subject deleted successfully');

        return response()->json(['success' => true]);
    }
}
