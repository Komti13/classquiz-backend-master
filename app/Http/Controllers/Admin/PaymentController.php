<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Payment;
use App\PaymentHistory;
use App\PaymentStatus;

class PaymentController extends Controller
{

    public function index()
    {
        $statusId = request('status');
        $paymentStatuses = PaymentStatus::all();
        $payments = Payment::select('payments.*')
            ->when($statusId, function ($query, $statusId) {
                return $query->join('payment_histories', 'payments.id', 'payment_histories.payment_id')
                    ->where('payment_histories.payment_status_id', $statusId)
                    ->where('payment_histories.id', function ($q) {
                        $q->select('id')
                            ->from('payment_histories')
                            ->whereColumn('payment_id', 'payments.id')
                            ->orderByDesc('updated_at')
                            ->limit(1);
                    });
            })
            ->with('user.roles', 'paymentMethod');

        if (request()->isXmlHttpRequest()) {
            return datatables()->eloquent($payments)->toJson();
        }

        return view('admin.payment.index', compact('paymentStatuses'));
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.payment.show', compact('payment'));
    }

    public function status($id)
    {
        request()->validate([
            'payment_status_id' => 'required|integer|exists:payment_statuses,id',
        ]);
        $payment = Payment::findOrFail($id);

        $paymentHistory = new PaymentHistory;
        $paymentHistory->payment_id = $payment->id;
        $paymentHistory->payment_status_id = request('payment_status_id');
        $paymentHistory->save();

        return response()->json([
            'success' => true,
            'message' => 'Status changed successfully'
        ]);
    }

}
