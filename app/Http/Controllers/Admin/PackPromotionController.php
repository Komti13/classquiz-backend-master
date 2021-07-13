<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pack;
use App\PackPromotion;

class PackPromotionController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $packPromotions = PackPromotion::query()->with('pack.level');
            return datatables()->eloquent($packPromotions)->toJson();
        }

        return view('admin.pack_promotion.index');
    }

    public function create()
    {
        $packs = Pack::pluck('name', 'id');
        return view('admin.pack_promotion.create', compact('packs'));
    }

    public function store()
    {
        request()->validate([
            'pack_id' => 'required|integer|exists:packs,id',
            'early_bird' => 'boolean',
            'start' => 'required|date',
            'end' => 'required|date',
            'value' => 'required|integer',
        ]);
        $packPromotion = new PackPromotion;
        $packPromotion->pack_id = request('pack_id');
        $packPromotion->early_bird = request('early_bird') ? 1 : 0;
        $packPromotion->start = request('start');
        $packPromotion->end = request('end');
        $packPromotion->value = request('value');

        $packPromotion->save();


        return redirect()->route('pack-promotions.index')
            ->with('success', 'Pack Promotion created successfully');
    }

    public function edit($id)
    {
        $packPromotion = PackPromotion::findOrFail($id);
        $packs = Pack::pluck('name', 'id');
        return view('admin.pack_promotion.edit', compact('packPromotion', 'packs'));
    }


    public function update($id)
    {
        $packPromotion = PackPromotion::findOrFail($id);
        request()->validate([
            'pack_id' => 'required|integer|exists:packs,id',
            'early_bird' => 'boolean',
            'start' => 'required|date',
            'end' => 'required|date',
            'value' => 'required|integer',
        ]);
        $packPromotion->pack_id = request('pack_id');
        $packPromotion->early_bird = request('early_bird') ? 1 : 0;
        $packPromotion->start = request('start');
        $packPromotion->end = request('end');
        $packPromotion->value = request('value');

        $packPromotion->save();

        return redirect()->route('pack-promotions.index')
            ->with('success', 'Pack Promotion edited successfully');
    }


    public function destroy($id)
    {
        PackPromotion::destroy($id);
        session()->flash('success', 'Pack Promotion deleted successfully');

        return response()->json(['success' => true]);
    }
}
