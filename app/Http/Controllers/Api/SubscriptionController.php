<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Payment as PaymentResource;
use App\Http\Resources\Subscription as SubscriptionResource;
use App\Pack;
use App\Payment;
use App\PaymentHistory;
use App\PaymentStatus;
use App\Subscription;
use App\Token;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Subscription
 *
 */
class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('checkToken');
    }

    /**
     * Store a newly created payment in storage.
     * @authenticated
     * @param \Illuminate\Http\Request $request
     * @return SubscriptionResource|\Illuminate\Http\JsonResponse
     */
    public function subscription(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'pack_id' => 'required|integer|exists:packs,id',
            'code' => 'string|max:255|required_if:payment_method_id,==,1',
            'amount' => 'integer|required_if:payment_method_id,==,1',
            'token' => 'nullable|string'
        ]);

        $user = Auth::guard('api')->user();
        //check valid pack
        $pack = Pack::find($request->pack_id);
        if (!$pack || !$pack->is_valid) {
            return response()->json(['error' => 'invalid.pack']);
        }
        //check token if exist and if it's user specific check user_id
        $token = null;
        if (request('token')) {
            $token = Token::where('token', $request->token)->first();
            if (!$token || !$token->is_valid || ($token->user_id && $token->user_id != $user->id)) {
                return response()->json(['error' => 'invalid.token']);
            }
            if ($token->used) {
                return response()->json(['error' => 'invalid.token']);
            }
        }
        //check if he doesnt have same pack
        foreach ($user->validSubscriptions as $subscription) {
            if ($subscription->pack->id == $pack->id) {
                return response()->json(['error' => 'already.subscribed']);
            }
        }

        $price = $pack->price;

        if ($pack->activePackPromotion()) {
            $price = $pack->activePackPromotion()->value;
        }

        $tokenValue = $token ? $token->value : 0;
        if ($token && !$token->private && !$token->user_id && $pack->packType->name !== 'Annual') {
            $tokenValue = 0;
        }

        if ($token && ($token->private || $token->user_id)) {
            if ($token->user_id) {
                $token->user_id = $user->id;
            }
            $token->used = true;
            $token->save();
        }

        $finalPrice = max($price - $tokenValue, 0);

        //else proceed to subscription

        $subscription = new Subscription;
        $subscription->user_id = $user->id;
        $subscription->pack_id = $pack->id;
        $subscription->token_id = optional($token)->id;
        $subscription->pack_promotion_id = optional($pack->activePackPromotion())->id;

        $subscription->save();

        //when using free vouchers
//        if ($tokenValue >= $finalPrice && $request->payment_method_id != 3) {
//            $subscription->is_free = true;
//            $subscription->save();
//        } else {
        switch ($request->payment_method_id) {
            case 1:
                $this->bankTransfer($request, $subscription->id, $finalPrice);
                break;
            case 2:
                $this->creditCard($request, $subscription->id, $finalPrice);
                break;
            case 3:
                $this->delivery($request, $subscription->id, $tokenValue);
                break;
        }
//        }

        return new SubscriptionResource($subscription);

    }

    public function bankTransfer(Request $request, $subscriptionId, $finalPrice)
    {
        $payment = new Payment;
        $payment->user_id = Auth::guard('api')->user()->id;
        $payment->code = $request->code;
        $payment->amount = $finalPrice;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->subscription_id = $subscriptionId;
        $payment->save();

        $paymentHistory = new PaymentHistory;
        $paymentHistory->payment_id = $payment->id;
        $paymentHistory->payment_status_id = PaymentStatus::where('name', 'Pending')->first()->id;
        $paymentHistory->save();
    }

    public function creditCard(Request $request, $subscriptionId, $finalPrice)
    {
        $payment = new Payment;
        $payment->user_id = Auth::guard('api')->user()->id;
        $payment->amount = $finalPrice;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->subscription_id = $subscriptionId;
        $payment->save();

        $paymentHistory = new PaymentHistory;
        $paymentHistory->payment_id = $payment->id;
        $paymentHistory->payment_status_id = PaymentStatus::where('name', 'Pending')->first()->id;
        $paymentHistory->save();
    }

    public function calculatePrice()
    {
        request()->validate([
            'pack_id' => 'required|string',
            'token' => 'nullable|string'
        ]);
        $user = Auth::guard('api')->user();
        //check valid pack
        $pack = Pack::find(request('pack_id'));
        if (!$pack || !$pack->is_valid) {
            return response()->json(['error' => 'invalid.pack']);
        }
        //check token if exist and if it's user specific check user_id
        $token = null;
        if (request('token')) {
            $token = Token::where('token', request('token'))->first();
            if (!$token || !$token->is_valid || ($token->user_id && $token->user_id != $user->id)) {
                return response()->json(['error' => 'invalid.token']);
            }
            if ($token->used) {
                return response()->json(['error' => 'invalid.token']);
            }
        }
        //check if he doesnt have same pack
        foreach ($user->validSubscriptions as $subscription) {
            if ($subscription->pack->id == $pack->id) {
                return response()->json(['error' => 'already.subscribed']);
            }
        }

        $price = $pack->price;

        if ($pack->activePackPromotion()) {
            $price = $pack->activePackPromotion()->value;
        }

        $tokenValue = $token ? $token->value : 0;
        if ($token && !$token->private && !$token->user_id && $pack->packType->name !== 'Annual') {
            $tokenValue = 0;
        }

        $finalPrice = max($price - $tokenValue, 0);

        return response()->json(['final_price' => $finalPrice]);
    }

    public function delivery(Request $request, $subscriptionId, $tokenValue)
    {
        $payment = new Payment;
        $payment->user_id = Auth::guard('api')->user()->id;
        $payment->amount = $tokenValue;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->subscription_id = $subscriptionId;
        $payment->save();

        $paymentHistory = new PaymentHistory;
        $paymentHistory->payment_id = $payment->id;
        $paymentHistory->payment_status_id = PaymentStatus::where('name', 'Accepted')->first()->id;
        $paymentHistory->save();
    }

    public function checkToken($token)
    {
        $status = 'Unused';
        if (!$token) {
            return response()->json('Token not provided');
        }
        $token = Token::where('token', $token)->first();
        if (!$token) {
            return response()->json('Does not exist');
        }
        if ($token->used) {
            $status = 'Used';
        }
        if (!$token->is_valid) {
            $status = 'Expired';
        }

        return response()->json($status);
    }
}
