<?php

namespace App\Http\Controllers\Admin;

use App\Payment;
use App\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $subscriptions = Subscription::query()->with('pack', 'user', 'payment', 'token');
            return datatables()->eloquent($subscriptions)->toJson();
        }

        return view('admin.subscription.index');
    }
}
