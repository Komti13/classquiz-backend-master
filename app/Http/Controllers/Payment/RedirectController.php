<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Subscription;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{

    public function index()
    {
        $subscriptionId = request("subscription_id");
        if (!$subscriptionId) {
            return view("payment.redirect.error", [
                "message" => "subscription_id is required"
            ]);
        }
        $subscription = Subscription::find($subscriptionId);
        if (!$subscription) {
            return view("payment.redirect.error", [
                "message" => "subscription_id is invalid"
            ]);
        }
        if (!$subscription->user->email) {
            return view("payment.redirect.error", [
                "message" => "User email is missing"
            ]);
        }
        $gpgUrl = config("gpg_checkout.url");
        $numSite = config("gpg_checkout.num_site");
        $password = config("gpg_checkout.password");
        $amount = $subscription->payment->amount * 1000;
        $devise = 'TND';
        $orderId = $subscription->id;
        $signture = sha1($numSite . $password . $orderId . $amount . $devise);
        $data = [
            "NumSite" => $numSite,
            "Password" => md5($password),
            "orderID" => $orderId,
            "Amount" => $amount,
            "Currency" => $devise,
            "Language" => "fr",
            "EMAIL" => $subscription->user->email ? $subscription->user->email : $subscription->user->parent->email,
            "CustLastName" => $subscription->user->name,
            "CustFirstName" => $subscription->user->name,
            "CustAddress" => $subscription->user->address ? $subscription->user->address : $subscription->user->parent->address,
            "CustTel" => $subscription->user->phone ? $subscription->user->phone : $subscription->user->parent->phone,
            "PayementType" => "1",
            "MerchandSession" => bcrypt($subscription->id),
            "orderProducts" => $subscription->pack->name,
            "signature" => $signture,
        ];
        return view("payment.redirect.index", compact("data", "gpgUrl"));
    }
}
