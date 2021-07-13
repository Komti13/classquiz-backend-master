<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Atlas;

class AtlasController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $atlases = Atlas::query();
            return datatables()->eloquent($atlases)->toJson();
        }

        return view('admin.atlas.index');
    }

    public function create()
    {
        return view('admin.atlas.create');
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'url' => 'required',
        ]);
        $atlas = new Atlas;
        $atlas->is_default = request('is_default') ? 1 : 0;
        $atlas->name = request('name');

        if (isset(request()->file()['url'])) {
            request()->validate(['url' => 'image|mimes:jpeg,png,jpg,gif|max:2048']);
            $urlName = time() . '.' . request('url')->getClientOriginalExtension();
            request('url')->move(public_path('uploads/icon/atlas/'), $urlName);
            $atlas->url = $urlName;
        } else {
            request()->validate(['url' => 'string|max:255']);
            $atlas->url = request('url');
        }


        $atlas->save();


        return redirect()->route('atlases.index')
            ->with('success', 'Atlas created successfully');
    }

    public function edit($id)
    {
        $atlas = Atlas::findOrFail($id);
        return view('admin.atlas.edit', compact('atlas'));
    }


    public function update($id)
    {
        $atlas = Atlas::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'url' => 'required',
        ]);

        $atlas->is_default = request('is_default') ? 1 : 0;
        $atlas->name = request('name');

        if (isset(request()->file()['url'])) {
            request()->validate(['url' => 'image|mimes:jpeg,png,jpg,gif|max:2048']);
            $urlName = time() . '.' . request('url')->getClientOriginalExtension();
            request('url')->move(public_path('uploads/icon/atlas/'), $urlName);
            $atlas->url = $urlName;
        } else {
            request()->validate(['url' => 'string|max:255']);
            $atlas->url = request('url');
        }
        $atlas->save();

        return redirect()->route('atlases.index')
            ->with('success', 'Atlas edited successfully');
    }


    public function destroy($id)
    {
        Atlas::destroy($id);
        session()->flash('success', 'Atlas deleted successfully');

        return response()->json(['success' => true]);
    }
}
