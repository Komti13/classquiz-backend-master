<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\PaymentHistory;
use App\PaymentStatus;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    public function notification(Request $request)
    {
        $subscription = Subscription::findOrFail($request->PAYID);
        $signature = sha1($request->TransStatus . $subscription->id . config("gpg_checkout.password"));
        if ($request->Signature != $signature) {
            Log::alert("Insecure payment callback");
            return;
        }
        if ($subscription->payment->current_status->name == 'Accepted') {
            Log::debug("Payment callback for already approved code {$subscription->id}");
            //todo: make an email to inform admin about this
            return;
        }
        if ($request->TransStatus == "00" && $request->TotalAmount / 1000 == $subscription->payment->amount) {
            $accepted = PaymentStatus::whereName('Accepted')->first();
            $this->setStatus($subscription, $accepted);
        } elseif ($request->TransStatus == "06" || $request->TransStatus == "05") {
            $refused = PaymentStatus::whereName('Refused')->first();
            $this->setStatus($subscription, $refused);
        }
        $subscription->save();

        return response()->json([
            'message' => 'Notification received successfully'
        ]);
    }

    private function setStatus($subscription, $paymentStatus)
    {
        $paymentHistory = new PaymentHistory;
        $paymentHistory->payment_id = $subscription->payment->id;
        $paymentHistory->payment_status_id = $paymentStatus->id;
        $paymentHistory->save();
    }

    public function success()
    {
        return view('payment.callback.success');
    }

    public function failure()
    {
        return view('payment.callback.failure');
    }
}
