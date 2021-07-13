<?php

namespace App\Http\Controllers\Api;

use App\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethod as PaymentMethodResource;

/**
 * @resource Payment
 *
 */
class PaymentMethodController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of payment methods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return PaymentMethodResource::collection($paymentMethods);
    }
}
