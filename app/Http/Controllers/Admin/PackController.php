<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\Http\Controllers\Controller;
use App\Pack;
use App\PackType;
use App\Level;

class PackController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $packs = Pack::query()->with('packType', 'level');
            return datatables()->eloquent($packs)->toJson();
        }

        return view('admin.pack.index');
    }

    public function create()
    {

        $packTypes = PackType::pluck('name', 'id');
        $levels = Level::all();
        $chapters = Chapter::pluck('name', 'id');
        return view('admin.pack.create', compact('levels', 'packTypes', 'chapters'));
    }

    public function store()
    {

        request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'order' => 'nullable|integer',
            'enabled' => 'boolean',
            'validity_start' => 'required|date',
            'validity_end' => 'required|date',
            'pack_type_id' => 'required|exists:pack_types,id',
            'level_id' => 'required|exists:levels,id',
            'chapters' => 'nullable|array',
        ]);
        $pack = new Pack;
        $pack->name = request('name');
        $pack->description = request('description');
        $pack->price = request('price');
        $pack->validity_start = request('validity_start');
        $pack->validity_end = request('validity_end');
        $pack->pack_type_id = request('pack_type_id');
        $pack->level_id = request('level_id');
        $pack->enabled = request('enabled') ? 1 : 0;
        $pack->order = request('order');

        $pack->save();

        $pack->chapters()->attach(request('chapters'));


        return redirect()->route('packs.index')
            ->with('success', 'Pack created successfully');
    }

    public function show($id)
    {

        $pack = Pack::findOrFail($id);
        return view('admin.pack.show', compact('pack'));
    }

    public function edit($id)
    {

        $pack = Pack::findOrFail($id);
        $levels = Level::all();
        $packTypes = PackType::pluck('name', 'id');
        $chapters = Chapter::pluck('name', 'id');
        return view('admin.pack.edit', compact('pack', 'levels', 'packTypes', 'chapters'));
    }


    public function update($id)
    {
        $pack = Pack::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'order' => 'nullable|integer',
            'enabled' => 'boolean',
            'validity_start' => 'required|date',
            'validity_end' => 'required|date',
            'pack_type_id' => 'required|exists:pack_types,id',
            'level_id' => 'required|exists:levels,id',
            'chapters' => 'nullable|array',
        ]);
        $pack->name = request('name');
        $pack->description = request('description');
        $pack->price = request('price');
        $pack->validity_start = request('validity_start');
        $pack->validity_end = request('validity_end');
        $pack->pack_type_id = request('pack_type_id');
        $pack->level_id = request('level_id');
        $pack->enabled = request('enabled') ? 1 : 0;
        $pack->order = request('order');

        $pack->save();

        $pack->chapters()->sync(request('chapters'));

        return redirect()->route('packs.index')
            ->with('success', 'Pack edited successfully');
    }


    public function destroy($id)
    {
        Pack::destroy($id);
        session()->flash('success', 'Pack deleted successfully');

        return response()->json(['success' => true]);
    }
}
