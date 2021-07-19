<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Delegation;
use App\Governorate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCall as UserCallResource;
use App\Http\Resources\Subscription as SubResource;

use App\Http\Resources\SalesInfo as SalesResource;

use App\SalesInfo;
use App\Subscription;
use App\UserCall;
use PhpParser\Node\Expr\Cast\Object_;

class LogisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $calls = Subscription::with('user.level', 'user.country', 'user.usercalls','user.usercalls.userStatus', 'user.usercalls.salesInfo', 'user.usercalls.salesInfo.salesManager', 'user.usercalls.salesInfo.source', 'user.usercalls.salesInfo.ad', 'payment', 'payment.paymentMethod', 'payment.delivery')->paginate();
        return SubResource::collection($calls);
   
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}