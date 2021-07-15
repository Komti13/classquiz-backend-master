<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\User as UserResource;
use App\UserCall;
use App\Subscription;

use App\Http\Resources\UserCall as UserCallResource;




class LogisticController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $calls = Subscription::with('user.level', 'user.usercalls', 'user.usercalls.salesInfo', 'user.usercalls.salesInfo.salesManager', 'user.usercalls.salesInfo.source', 'user.usercalls.salesInfo.ad', 'payment', 'payment.paymentMethod','payment.delivery');

            return datatables()->eloquent($calls)->toJson();
        }

        return view('admin.logistics.index');
       



        
    }
    public function logistics()
    {
        return view('admin.logistics.index');
    }
}