<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Delegation;
use App\Governorate;

class DelegationController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $delegations = Delegation::query()->with('governorate');
            return datatables()->eloquent($delegations)->toJson();
        }

        return view('admin.delegation.index');
    }

    public function create()
    {
        $governorates = Governorate::pluck('name', 'id');
        return view('admin.delegation.create', compact('governorates'));
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'governorate_id' => 'required|exists:governorates,id',
        ]);
        $delegation = new Delegation;
        $delegation->name = request('name');
        $delegation->governorate_id = request('governorate_id');

        $delegation->save();


        return redirect()->route('delegations.index')
            ->with('success', 'Delegation created successfully');
    }

    public function edit($id)
    {

        $delegation = Delegation::findOrFail($id);
        $governorates = Governorate::pluck('name', 'id');
        return view('admin.delegation.edit', compact('delegation', 'governorates'));
    }


    public function update($id)
    {
        $delegation = Delegation::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'governorate_id' => 'required|exists:governorates,id',
        ]);
        $delegation->name = request('name');
        $delegation->governorate_id = request('governorate_id');

        $delegation->save();
        return redirect()->route('delegations.index')
            ->with('success', 'Delegation edited successfully');
    }


    public function destroy($id)
    {
        Delegation::destroy($id);
        session()->flash('success', 'Delegation deleted successfully');

        return response()->json(['success' => true]);
    }
}
