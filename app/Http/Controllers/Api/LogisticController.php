<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Delegation;
use App\Governorate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Pack;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCall as UserCallResource;
use App\Http\Resources\Subscription as SubResource;

use App\Http\Resources\SalesInfo as SalesResource;
use App\PackPromotion;
use App\SalesInfo;
use App\Subscription;
use App\UserCall;
use PhpParser\Node\Expr\Cast\Object_;
use App\Http\Resources\RendezVous as RendezResource;
use App\RendezVous;

class LogisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subs = Subscription::where('id', 9606)->with('user.usercalls.salesInfo.source', 'payment.paymentMethod', 'token', 'user.usercalls.sms')->get();
        return SubResource::collection($subs);
    //     $rendez = RendezVous::where('subscription_id', 9583)->get();
    //    return RendezResource::collection($rendez);
        // $calls = Subscription::orderBy('id', 'DESC')->with('user.usercalls.userStatus', 'user.usercalls.salesInfo.source', 'user.usercalls.salesInfo.ad', 'pack.packType', 'pack.level', 'payment.paymentMethod', 'payment.delivery','child')->paginate();
        
        // return SubResource::collection($calls);
        // $calls = Subscription::where('id',9002)->with('user.usercalls.sms')->get();
    //     $packs = Pack::select([
    //          'id', 'name', 'level_id','price'
    //     ])->get();
    //     $promo = PackPromotion::select([
    //         'id', 'pack_id', 'value'
    //    ])->get();

    //     return response()->json([
    //         'packs' => $packs,
    //         'promo' => $promo,
    //     ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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