<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Level;

class LevelController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $levels = Level::query();
            return datatables()->eloquent($levels)->toJson();
        }

        return view('admin.level.index');
    }

    public function create()
    {
        return view('admin.level.create');
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'enabled' => 'required|boolean',
        ]);
        $level = new Level;
        $level->name = request('name');
        $level->number = request('number');
        $level->enabled = request('enabled');

        $level->save();


        return redirect()->route('levels.index')
            ->with('success', 'Level created successfully');
    }

    public function edit($id)
    {

        $level = Level::findOrFail($id);
        return view('admin.level.edit', compact('level'));
    }


    public function update($id)
    {
        $level = Level::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'enabled' => 'required|boolean',
        ]);
        $level->name = request('name');
        $level->number = request('number');
        $level->enabled = request('enabled');

        $level->save();


        return redirect()->route('levels.index')
            ->with('success', 'Level edited successfully');
    }


    public function destroy($id)
    {
        Level::destroy($id);
        session()->flash('success', 'Level deleted successfully');

        return response()->json(['success' => true]);
    }
}
