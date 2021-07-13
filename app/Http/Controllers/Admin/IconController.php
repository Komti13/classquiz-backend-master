<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Icon;
use App\Atlas;

class IconController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $icons = Icon::query();
            return datatables()->eloquent($icons)->toJson();
        }

        return view('admin.icon.index');
    }

    public function create()
    {
        $atlases = Atlas::pluck('name', 'id');
        return view('admin.icon.create', compact('atlases'));
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|integer',
            'is_default' => 'nullable|boolean',
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


        return redirect()->route('icons.index')
            ->with('success', 'Icon created successfully');
    }

    public function edit($id)
    {
        $icon = Icon::findOrFail($id);
        $atlases = Atlas::pluck('name', 'id');
        return view('admin.icon.edit', compact('icon', 'atlases'));
    }


    public function update($id)
    {
        $icon = Icon::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|integer',
            'is_default' => 'nullable|boolean',
            'atlas_id' => 'required|exists:atlases,id',
            'direct_url' => 'nullable',
            'index' => 'required|integer'
        ]);

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

        return redirect()->route('icons.index')
            ->with('success', 'Icon edited successfully');
    }


    public function destroy($id)
    {
        Icon::destroy($id);
        session()->flash('success', 'Icon deleted successfully');

        return response()->json(['success' => true]);
    }
}
