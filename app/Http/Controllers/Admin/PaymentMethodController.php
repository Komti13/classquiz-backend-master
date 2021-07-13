<?php

namespace App\Http\Controllers\Admin;

use App\Chapter;
use App\Http\Controllers\Controller;
use App\PaymentMethod;
use App\PaymentMethodType;
use App\Level;

class PaymentMethodController extends Controller
{

    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $paymentMethods = PaymentMethod::query();
            return datatables()->eloquent($paymentMethods)->toJson();
        }

        return view('admin.payment_method.index');
    }

    public function create()
    {
        return view('admin.payment_method.create');
    }

    public function store()
    {

        request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);
        $paymentMethod = new PaymentMethod;
        $paymentMethod->name = request('name');
        $paymentMethod->description = request('description');

        $paymentMethod->save();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method created successfully');
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('admin.payment_method.edit', compact('paymentMethod'));
    }


    public function update($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);
        $paymentMethod->name = request('name');
        $paymentMethod->description = request('description');

        $paymentMethod->save();

        return redirect()->route('payment-methods.index')
            ->with('success', 'Payment method edited successfully');
    }


    public function destroy($id)
    {
        PaymentMethod::destroy($id);
        session()->flash('success', 'Payment method deleted successfully');

        return response()->json(['success' => true]);
    }
}
