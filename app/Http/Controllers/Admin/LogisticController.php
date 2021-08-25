<?php

namespace App\Http\Controllers\Admin;

use App\Ad;
use App\Country;
use App\Delivery;
use App\Feedback;
use App\Governorate;
use App\Http\Controllers\Controller;
use App\Http\Resources\Subscription as SubResource;
use App\Http\Resources\RendezVous as RendezResource;
use App\Level;
use App\Pack;
use App\PackPromotion;
use App\Payment;
use App\PaymentHistory;
use App\PaymentMethod;
use App\PaymentStatus;
use App\RendezVous;
use App\Role;
use App\SalesInfo;
use App\School;
use App\Sms;
use App\Source;
use App\Subscription;
use App\Support;
use App\Token;
use App\User;
use App\UserCall;
use App\UserStatus;
use Carbon\Carbon;
use PDF;

class LogisticController extends Controller
{
    public function index()
    {
        // dd(request()->all());
        $packs = Pack::where('enabled', 1)->groupBy('name')->get('name');
        $status = UserStatus::all();
        if (request()->isXmlHttpRequest()) {
            $calls = Subscription::orderBy('subscriptions.id', 'DESC')
                ->with(
                    'user.usercalls.userStatus',
                    'user.usercalls.salesInfo.source',
                    'user.usercalls.salesInfo.ad',
                    'pack.packType',
                    'pack.level',
                    'payment.paymentMethod',
                    'delivery',
                    'child'
                )
                ->when(request('created_at_from'), function ($q) {
                    $date=date('Y-m-d',strtotime(request('created_at_from')));
                    
                    $q->where('subscriptions.created_at', '>=', $date);
                })
                ->when(request('created_at_to'), function ($q) {
                    $date=date('Y-m-d',strtotime(request('created_at_to')));
                    
                    $q->where('subscriptions.created_at', '<=', $date);
                })
                ->when(request('status'), function ($q) {
                    $users=UserCall::where('user_status_id', request('status'))->pluck('user_id');
                    $q->whereIn('subscriptions.user_id',$users);
                });
            return datatables()->of($calls)->toJson();
        }

        return view('admin.logistics.create.index', compact( 'packs', 'status'));
    }
    public function create()
    {
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $sources = Source::pluck('source', 'id');
        $levels = Level::pluck('name', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('paymethod', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id');
        $cities = Governorate::pluck('name', 'id');
        $allpacks = Pack::select([
            'id', 'name', 'level_id', 'price',
        ])->get();
        $promos = PackPromotion::select(
            [
                'id',
                'pack_id',
                'value',
            ]
        )->get();
        return view('admin.logistics.create.create', compact('schools', 'countries', 'sources', 'ads', 'methodes', 'payStatus', 'packs', 'status', 'cities', 'levels', 'promos', 'allpacks'));
    }

    public function editform($id)
    {
        return view('admin.logistics.update.form_template', compact('id'));
    }
    public function edit($id)
    {
        $subs = Subscription::where('id', $id)->get();
        $sub = SubResource::collection($subs);
        $s = $sub[0];
        $r = null;
        $rendez = RendezVous::where('subscription_id', $id)->get();
        $r_vous = RendezResource::collection($rendez);
        if (!empty($r_vous[0])) {
            $r = $r_vous[0]->rendez_vous;
        } else {
            $r = $s->conversion_date;
        }
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $sources = Source::pluck('source', 'id');
        $levels = Level::pluck('name', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('paymethod', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id');
        $cities = Governorate::pluck('name', 'id');
        $allpacks = Pack::select([
            'id', 'name', 'level_id', 'price',
        ])->get();
        $promos = PackPromotion::select(
            [
                'id',
                'pack_id',
                'value',
            ]
        )->get();
        return view('admin.logistics.update.update', compact('schools', 'countries', 'sources', 'ads', 'methodes', 'payStatus', 'packs', 'status', 'cities', 'levels', 'promos', 'allpacks', 's', 'id', 'r'));
    }
    public function update($id, $source)
    {
        // dd($id);
        $status = UserStatus::pluck('name', 'id');
        $cities = Governorate::pluck('name', 'id');
        $subs = Subscription::where('id', $id)->with('user.usercalls.salesInfo.source', 'payment.paymentMethod', 'token', 'user.usercalls.sms')->get();
        $sub = SubResource::collection($subs);
        $promos = PackPromotion::select(
            [
                'id',
                'pack_id',
                'value',
            ]
        )->get();
        $allpacks = Pack::select([
            'id', 'name', 'level_id', 'price',
        ])->get();


        if ($source == 'first') {
            // if (request('phone2')!=null) {
            //     request()->validate([
            //         'name' => 'required|string|max:255',
            //         'phone' => 'required|string|max:8',
    
            //     ]);
            // }
           
            // return request()->all();
            $nbchild = 0;
            $user = User::findOrFail($sub[0]->user->id);
            $user->name = request('name');
            $user->phone = request('phone');
            if (request('phone2')!=null) {
                $user->phone2 = request('phone2');
            }
            if (request('city_id') != null) {
                $user->address = request('address') . " " . $cities[request('city_id')];
                $user->city_id = request('city_id');
            }
            if (request('country_id') != null) {
                $user->country_id = request('country_id');
            }
            if (request('nb_children') > 0) {
                $nbchild = request('nb_children');
                $user->nb_children = request('nb_children');
            }
            $user->save();

            // sales Info 

            $sales_info = SalesInfo::findOrFail($sub[0]->user->usercalls->salesInfo->id);
            if (request('source') > 0) {
                $sales_info->source_id = request('source');
            }
            if (request('ad') > 0) {
                $sales_info->ad_id = request('ad');
            }
            $sales_info->sales_manager_id = auth()->guard('admin')->user()->id;
            $sales_info->save();
            //subscription get
            $subscription = Subscription::findOrFail($sub[0]->id);

            // usercall

            $userCall = UserCall::findOrFail($sub[0]->user->usercalls->id);
            $sms = null;
            if ($sub[0]->user->usercalls->sms == null) {
                $sms = new Sms;
            } else {
                $sms = Sms::findOrFail($sub[0]->user->usercalls->sms->id);
            }
            if (trim(request('sms_text'), ' ') != '' && request('type') != "") {
                $sms->text = request('sms_text');
                $sms->type = request('type');
                $sms->save();
                $userCall->sms_sent = true;
                $userCall->sms_id = $sms->id;
            }
            $userCall->notes = request('notes');
            $userCall->sales_info_id = $sales_info->id;
            $userCall->user_id = $user->id;
            if (request('status') != null) {
                foreach ($status as $key => $value) {
                    if ($key == (int)request('status')) {
                        $rendez_vous = RendezVous::where('subscription_id', $sub[0]->id)->first();
                        if ($rendez_vous != null) {
                            if (request('delivery_date') != null) {
                                if (request('status') == 7 || request('status') == 8 || request('status') == 9) {
                                    $subscription->conversion_date = request('delivery_date');
                                }
                                $rendez_vous->rendez_vous = request('delivery_date');
                                $rendez_vous->objectif = $value;
                                $rendez_vous->save();
                            }
                        }
                    }
                }
            }
            $userCall->user_status_id = request('status');
            $userCall->calltype = 'Sale';
            $userCall->conversation_date = request('call_date');
            $userCall->save();



            //Child
            $child_exist = false;
            $child = null;
            if (trim(request('childname'), ' ') != '') {
                if ($sub[0]->child != null) {
                    $child = User::findOrFail($sub[0]->child->id);
                    $child->name = request('childname');
                    $child->level_id = request('level_id');
                    $child->save();
                } else {
                    $child = new User;
                    $child->name = request('childname');
                    if (request('level_id') != null) {
                        $child->level_id = request('level_id');
                    }
                    $child->save();
                    $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                    $user->students()->sync($child->id);
                }
                $subscription->child_id = $child->id;
                $child_exist = true;
            }
            //double subscription
            if ($subscription->pack_id < 46 || $subscription->pack_id > 51) {

                if (request('next') != '' && request('pack') > 46 && request('pack') < 51) {
                    $new_subscription = new Subscription;
                    $new_subscription->pack_id = request('pack');
                    $new_subscription->user_id = $user->id;
                    $new_subscription->child_id = $child->id;
                    $token = new Token;
                    $token->token = request('next') . '*';
                    $token->validity_start = Carbon::now()->month(8)->addYears(1);
                    $token->validity_end = Carbon::now()->month(8)->addYears(2);
                    foreach ($promos as $promo) {
                        if ($promo->pack_id == (int)request('pack')) {
                            $token->value = $promo->value;
                        }
                    }
                    if ($token->value == null) {
                        foreach ($allpacks as $pack) {
                            if ($pack->id == (int)request('pack')) {
                                $token->value = $pack->price;
                            }
                        }
                    }
                    $token->user_id = $user->id;
                    $token->save();
                    $new_subscription->token_id = $token->id;
                    $new_subscription->conversion_date = $subscription->conversion_date;
                    $new_subscription->save();
                    if ((int)request('paymethod') > 0) {
                        $payment = new Payment;
                        $payment->amount = request('amount');
                        $payment->user_id = $user->id;
                        $payment->payment_method_id = request('paymethod');
                        $payment->subscription_id = $new_subscription->id;
                        $payment->save();
                        $history = new PaymentHistory;
                        $history->payment_id = $payment->id;
                        $history->payment_status_id = 1;
                        $history->save();
                        $delivery = new Delivery;
                        if (request('fees') == 'on') {
                            $delivery->delivery_fees = request('fees');
                        } else {
                            $delivery->delivery_fees = 'false';
                        }
                        if (request('double') == 'on') {
                            $delivery->double_delivery = request('double');
                        } else {
                            $delivery->double_delivery = 'false';
                        }
                        if (request('delstatus') != null) {
                            $delivery->delivery_status = request('delstatus');
                        }
                        $delivery->payment_id = $payment->id;
                        $delivery->subscription_id = $new_subscription->id;
                        $delivery->save();
                    }
                }
            }
            //subscription 
            if (request('pack') != null) {
                if (request('pack') != '---') {
                    $subscription->pack_id = request('pack');
                } else {
                    $subscription->pack_id = 52;
                }
            }
            $subscription->save();
            //payment
            $payment = null;
            if ((int)request('paymethod') > 0) {
                $payment = Payment::where('subscription_id', $subscription->id)->first();
                if ($payment == null) {
                    $payment = new Payment;
                }
                $payment->amount = request('amount');
                $payment->user_id = $user->id;
                $payment->payment_method_id = request('paymethod');
                $payment->subscription_id = $subscription->id;
                $payment->save();
                $history = PaymentHistory::where('payment_id', $payment->id)->first();
                if ($history == null) {
                    $history = new PaymentHistory;
                }
                $history->payment_id = $payment->id;
                $history->payment_status_id = 1;
                $history->save();
                $delivery = Delivery::where('subscription_id', $subscription->id)->first();
                if ($delivery == null) {
                    $delivery = new Delivery;
                }
                if (request('fees') == 'on') {
                    $delivery->delivery_fees = request('fees');
                } else {
                    $delivery->delivery_fees = 'false';
                }
                if (request('double') == 'on') {
                    $delivery->double_delivery = request('double');
                } else {
                    $delivery->double_delivery = 'false';
                }
                if ($payment != null) {
                    $delivery->payment_id = $payment->id;
                }
                if (request('delstatus') != null) {
                    $delivery->delivery_status = request('delstatus');
                }
                $delivery->subscription_id = $subscription->id;
                $delivery->save();
            }

            if ($nbchild > 0) {
                for ($i = 1; $i < $nbchild; $i++) {
                    $child = null;
                    $current_token = null;
                    if (trim(request('childname' . $i), ' ') != '') {
                        $child_exist = true;
                        $child = new User;
                        $child->name = request('childname' . $i);
                        if (request('level_id' . $i)) {
                            $child->level_id = request('level_id' . $i);
                        }

                        $child->save();
                        $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                        $user->students()->sync($child->id);

                        $current_token = new Token;
                        $current_token->token = request('current' . $i);
                        $current_token->validity_start = Carbon::now()->month(8);
                        $current_token->validity_end = Carbon::now()->addYears(1);
                        foreach ($allpacks as $pack) {
                            if ($pack->id == (int)request('pack' . $i)) {
                                $current_token->value = $pack->price;
                            }
                        }

                        $current_token->user_id = $user->id;
                        $current_token->save();

                        if (request('next' . $i) != null) {
                            $next_token = new Token;
                            $next_token->token = request('next' . $i) . '*';
                            $next_token->validity_start = Carbon::now()->month(8)->addYears(1);
                            $next_token->validity_end = Carbon::now()->addYears(2);
                            $next_token->value = $current_token->value;
                            $next_token->user_id = $user->id;
                            $next_token->save();
                            $subscription = new Subscription;
                            if (request('pack' . $i) != '---' && request('pack' . $i) != null && request('pack' . $i) != "null") {
                                $subscription->pack_id = request('pack' . $i);
                            } else {
                                $subscription->pack_id = 52;
                            }
                            $subscription->user_id = $user->id;
                            $subscription->child_id = $child->id;
                            $subscription->token_id = $next_token->id;
                            $subscription->save();

                            $payment = null;
                            if ((int)request('paymethod') > 0) {
                                $payment = new Payment;
                                $payment->amount = request('amount');
                                $payment->user_id = $user->id;
                                $payment->payment_method_id = request('paymethod');
                                $payment->subscription_id = $subscription->id;
                                $payment->save();
                                $history = new PaymentHistory;
                                $history->payment_id = $payment->id;
                                $history->payment_status_id = 1;
                                $history->save();
                                $delivery = new Delivery;
                                if (request('fees') == 'on') {
                                    $delivery->delivery_fees = request('fees');
                                } else {
                                    $delivery->delivery_fees = 'false';
                                }
                                if (request('double') == 'on') {
                                    $delivery->double_delivery = request('double');
                                } else {
                                    $delivery->double_delivery = 'false';
                                }
                                if (request('delstatus') != null) {
                                    $delivery->delivery_status = request('delstatus');
                                }
                                $delivery->payment_id = $payment->id;
                                $delivery->subscription_id = $subscription->id;
                                $delivery->save();
                            }
                        }
                        if ($child_exist) {
                            $subscription = new Subscription;
                            if (request('pack' . $i) != '---') {
                                $subscription->pack_id = request('pack' . $i);
                            } else {
                                $subscription->pack_id = 52;
                            }
                            $subscription->user_id = $user->id;
                            if ($child != null) {
                                $subscription->child_id = $child->id;
                            }
                            if ($current_token != null) {
                                $subscription->token_id = $current_token->id;
                            }
                            if (request('delivery_date')) {
                                if (request('status') != null) {
                                    if (request('status') == '7' || request('status') == '8' || request('status') == '9') {
                                        $subscription->conversion_date = request('delivery_date');
                                    }
                                }
                            }


                            $subscription->save();

                            $payment = new Payment;
                            if ((int)request('paymethod') > 0) {
                                $payment->amount = request('amount');
                                $payment->user_id = $user->id;
                                $payment->payment_method_id = request('paymethod');
                                $payment->subscription_id = $subscription->id;
                                $payment->save();
                                $history = new PaymentHistory;
                                $history->payment_id = $payment->id;
                                $history->payment_status_id = 1;
                                $history->save();
                                $delivery = new Delivery;
                                if (request('fees') == 'on') {
                                    $delivery->delivery_fees = request('fees');
                                } else {
                                    $delivery->delivery_fees = 'false';
                                }
                                if (request('double') == 'on') {
                                    $delivery->double_delivery = request('double');
                                } else {
                                    $delivery->double_delivery = 'false';
                                }
                                if (request('delstatus') != null) {
                                    $delivery->delivery_status = request('delstatus');
                                }
                                $delivery->payment_id = $payment->id;
                                $delivery->subscription_id = $subscription->id;
                                $delivery->save();
                            }
                            $child_exist = false;
                        }
                    }
                }
            }
        }
    }

    public function store($source)
    {
        $status = UserStatus::pluck('name', 'id');
        $cities = Governorate::pluck('name', 'id');
        $allpacks = Pack::select([
            'id', 'name', 'level_id', 'price',
        ])->get();
        $nbchild = 0;
        if ($source == 'first') {
            request()->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:8',

            ]);
            // return request()->all();

            // user 
            // dd(request('status'));
            if (request('nb_children') > 0) {
                $nbchild = request('nb_children');
            }
            $user = new User;
            $user->name = request('name');
            $user->phone = request('phone');
            if (request('phone2')) {
                $user->phone2 = request('phone2');
            }
            if (request('city_id') != null) {
                $user->address = request('address') . " " . $cities[request('city_id')];
                $user->city_id = request('city_id');
            }
            $user->country_id = 1;

            if ($nbchild > 0) {
                $user->nb_children = $nbchild;
            }
            $user->save();
            $user->roles()->sync(Role::where('name', 'PARENT')->first());
            // sales Info 
            $sales_info = new SalesInfo;
            if (request('source') > 0) {
                $sales_info->source_id = request('source');
            }
            if (request('ad') > 0) {
                $sales_info->ad_id = request('ad');
            }
            $sales_info->sales_manager_id = auth()->guard('admin')->user()->id;
            $sales_info->save();
            // usercall
            $userCall = new UserCall;
            $userCall->sms_sent = false;
            $sms = new Sms;
            if (trim(request('sms_text'), ' ') != '' && request('type') != "") {
                $sms->text = request('sms_text');
                $sms->type = request('type');
                $sms->save();
                $userCall->sms_sent = true;
                $userCall->sms_id = $sms->id;
            }

            $userCall->notes = request('notes');
            $userCall->sales_info_id = $sales_info->id;
            $userCall->user_id = $user->id;
            $userCall->user_status_id = request('status');
            $userCall->calltype = 'Sale';
            $userCall->conversation_date = request('call_date');
            $userCall->save();

            $child_exist = true;
            if ($nbchild > 0) {
                for ($i = 0; $i < $nbchild; $i++) {
                    $child = null;
                    $current_token = null;
                    if (request('childname' . $i)) {
                        $child_exist = true;
                        $child = new User;
                        $child->name = request('childname' . $i);
                        if (request('level_id' . $i)) {
                            $child->level_id = request('level_id' . $i);
                        }

                        $child->save();
                        $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                        $user->students()->sync($child->id);

                        $current_token = new Token;
                        $current_token->token = request('current' . $i);
                        $current_token->validity_start = Carbon::now()->month(8);
                        $current_token->validity_end = Carbon::now()->addYears(1);
                        foreach ($allpacks as $pack) {
                            if ($pack->id == (int)request('pack' . $i)) {
                                $current_token->value = $pack->price;
                            }
                        }

                        $current_token->user_id = $user->id;
                        $current_token->save();

                        if (request('next' . $i) != null) {
                            $next_token = new Token;
                            $next_token->token = request('next' . $i) . '*';
                            $next_token->validity_start = Carbon::now()->month(8)->addYears(1);
                            $next_token->validity_end = Carbon::now()->addYears(2);
                            $next_token->value = $current_token->value;
                            $next_token->user_id = $user->id;
                            $next_token->save();
                            $subscription = new Subscription;
                            if (request('pack' . $i) != '---' && request('pack' . $i) != null && request('pack' . $i) != "null") {
                                $subscription->pack_id = request('pack' . $i);
                            } else {
                                $subscription->pack_id = 52;
                            }
                            $subscription->user_id = $user->id;
                            $subscription->child_id = $child->id;
                            $subscription->token_id = $next_token->id;
                            $subscription->save();
                            if (request('status') != null) {
                                foreach ($status as $key => $value) {
                                    if ($key == (int)request('status')) {
                                        $rendez_vous = new RendezVous;
                                        if ($rendez_vous != null) {
                                            if (request('delivery_date') != null) {
                                                if (request('status') == '7' || request('status') == '8' || request('status') == '9') {
                                                    $subscription->conversion_date = request('delivery_date');
                                                }
                                                $rendez_vous->rendez_vous = request('delivery_date');
                                                $rendez_vous->objectif = $value;
                                                $rendez_vous->user_id = $user->id;
                                                $rendez_vous->sales_man_id = auth()->guard('admin')->user()->id;
                                                $rendez_vous->subscription_id = $subscription->id;
                                                $rendez_vous->save();
                                            }
                                        }
                                    }
                                }
                            }
                            $subscription->save();
                            $payment = null;
                            if ((int)request('paymethod') > 0) {
                                $payment = new Payment;
                                $payment->amount = request('amount');
                                $payment->user_id = $user->id;
                                $payment->payment_method_id = request('paymethod');
                                $payment->subscription_id = $subscription->id;
                                $payment->save();
                                $history = new PaymentHistory;
                                $history->payment_id = $payment->id;
                                $history->payment_status_id = 1;
                                $history->save();
                                $delivery = new Delivery;
                                if (request('fees') == 'on') {
                                    $delivery->delivery_fees = request('fees');
                                } else {
                                    $delivery->delivery_fees = 'false';
                                }
                                if (request('double') == 'on') {
                                    $delivery->double_delivery = request('double');
                                } else {
                                    $delivery->double_delivery = 'false';
                                }
                                $delivery->payment_id = $payment->id;
                                $delivery->subscription_id = $subscription->id;
                                $delivery->save();
                            }
                        }
                    }
                    if ($child_exist) {
                        $subscription = new Subscription;
                        if (request('pack' . $i) != '---') {
                            $subscription->pack_id = request('pack' . $i);
                        } else {
                            $subscription->pack_id = 52;
                        }
                        $subscription->user_id = $user->id;
                        if ($child != null) {
                            $subscription->child_id = $child->id;
                        }
                        if ($current_token != null) {
                            $subscription->token_id = $current_token->id;
                        }
                        $subscription->save();
                        if (request('status') != null) {
                            foreach ($status as $key => $value) {
                                if ($key == (int)request('status')) {
                                    $rendez_vous = new RendezVous;
                                    if ($rendez_vous != null) {
                                        if (request('delivery_date') != null) {

                                            if (request('status') == 7 || request('status') == 8 || request('status') == 9) {
                                                $subscription->conversion_date = request('delivery_date');
                                            }
                                            $rendez_vous->rendez_vous = request('delivery_date');
                                            $rendez_vous->objectif = $value;
                                            $rendez_vous->user_id = $user->id;
                                            $rendez_vous->sales_man_id = auth()->guard('admin')->user()->id;
                                            // dd($subscription->id);
                                            $rendez_vous->subscription_id = $subscription->id;
                                            $rendez_vous->save();
                                        }
                                    }
                                }
                            }
                        }
                        $subscription->save();
                        $payment = new Payment;
                        if ((int)request('paymethod') > 0) {
                            $payment->amount = request('amount');
                            $payment->user_id = $user->id;
                            $payment->payment_method_id = request('paymethod');
                            $payment->subscription_id = $subscription->id;
                            $payment->save();
                            $history = new PaymentHistory;
                            $history->payment_id = $payment->id;
                            $history->payment_status_id = 1;
                            $history->save();
                            $delivery = new Delivery;
                            if (request('fees') == 'on') {
                                $delivery->delivery_fees = request('fees');
                            } else {
                                $delivery->delivery_fees = 'false';
                            }
                            if (request('double') == 'on') {
                                $delivery->double_delivery = request('double');
                            } else {
                                $delivery->double_delivery = 'false';
                            }
                            $delivery->payment_id = $payment->id;
                            $delivery->subscription_id = $subscription->id;
                            $delivery->save();
                        }
                        $child_exist = false;
                    }
                }
            } else {
                $subscription = new Subscription;
                $subscription->user_id = $user->id;
                $subscription->pack_id = 52;
                $subscription->save();
                if (request('status') != null) {
                    foreach ($status as $key => $value) {
                        if ($key == (int)request('status')) {
                            $rendez_vous = new RendezVous;
                            if ($rendez_vous != null) {
                                if (request('delivery_date') != null) {
                                    if (request('status') == 7 || request('status') == 8 || request('status') == 9) {
                                        $subscription->conversion_date = request('delivery_date');
                                    }
                                    $rendez_vous->rendez_vous = request('delivery_date');
                                    $rendez_vous->objectif = $value;
                                    $rendez_vous->user_id = $user->id;
                                    $rendez_vous->sales_man_id = auth()->guard('admin')->user()->id;
                                    $rendez_vous->subscription_id = $subscription->id;
                                    $rendez_vous->save();
                                }
                            }
                        }
                    }
                }
                $subscription->save();
            }
        }
    }
    public function createPDF($type, $ids)
    {
        if ($type == 'code') {
            $pages = [];
            for ($i = 0; $i < count(explode(',', $ids)); $i++) {
                $calls = Subscription::where('id', explode(',', $ids)[$i])->get();
                $call = SubResource::collection($calls);
                $delivery = Delivery::where('subscription_id', $call[0]->id)->first();
                if ($delivery != null) {
                    $delivery->delivery_status = 'Delivery launched';
                    $delivery->save();
                }


                $userid = $call[0]->user->id;
                $token = null;
                if ($call[0]->token != null) {
                    $token = $call[0]->token->token;
                }

                $pages[$i]['id'] = $userid;
                $pages[$i]['token'] = $token;
            }
            // $tok = substr($token, 0, 9);
            $pdf = PDF::loadView('admin.logistics.pdf.code', ['pages' => $pages]);
            $fileName = 'codes.pdf';
            $pdf->save('C:\Users\DELL\Desktop\classquiz-backend-master\storage\pdf' . '\/' . $fileName);

            $pdf = 'C:\Users\DELL\Desktop\classquiz-backend-master\storage\pdf' . '\/' . $fileName;
            $headers = array(
                'Content-Type: application/pdf',
            );
            return response()->download($pdf, $fileName, $headers)->deleteFileAfterSend(true);
        } else if ($type == 'ticket') {
            $pages = [];
            for ($i = 0; $i < count(explode(',', $ids)); $i++) {
                $calls = Subscription::where('id', explode(',', $ids)[$i])->get();
                $call = SubResource::collection($calls);
                $delivery = Delivery::where('subscription_id', $call[0]->id)->first();
                if ($delivery != null) {
                    $delivery->delivery_status = 'Delivery launched';
                    $delivery->save();
                }
                $userid = $call[0]->user->id;
                $pack = $call[0]->pack->name;
                $level = $call[0]->pack->level->name;
                $date = $call[0]->created_at;
                $price = $call[0]->pack->price;
                if ($call[0]->payment != null) {
                    $amount = $call[0]->payment->amount;
                } else {
                    $amount = '?';
                }
                $pages[$i]['id'] = $userid;
                $pages[$i]['pack'] = $pack;
                $pages[$i]['level'] = $level;
                $pages[$i]['date'] = $date;
                $pages[$i]['price'] = $price;
                $pages[$i]['amount'] = $amount;
            }

            $fileName = 'tickets.pdf';
            $data = ['pages' => $pages];
            $pdf = PDF::loadView('admin.logistics.pdf.ticket', $data);
            $pdf->save('C:\Users\DELL\Desktop\classquiz-backend-master\storage\pdf' . '\/' . $fileName);

            $pdf = 'C:\Users\DELL\Desktop\classquiz-backend-master\storage\pdf' . '\/' . $fileName;
            $headers = array(
                'Content-Type:text/html; charset=utf-8',
            );
            return response()->download($pdf, $fileName, $headers)->deleteFileAfterSend(true);
        }
    }

    public function dataSheets()
    {
        $status = UserStatus::pluck('name', 'id');
        $sources = Source::pluck('source', 'id');
        $ads = Ad::pluck('type', 'id');
        $levels = Level::pluck('name', 'id');
        $packs = Pack::all();
        $methodes = PaymentMethod::pluck('paymethod', 'id');

        $path = storage_path() . "/json/data.json";
        $json = json_decode(file_get_contents($path), true);

        for ($i = 1; $i < 25; $i++) {
            $time = strtotime($json['values'][$i][3]);
            $date = date('Y-m-d', $time);
            $today = Carbon::today()->format('Y-m-d');
            if ($today == $date) {
                $user = new User;
                if ($json['values'][$i][1] != '') {
                    $user->name = $json['values'][$i][1];
                }
                if ($json['values'][$i][2] != '') {
                    $user->phone = $json['values'][$i][2];
                }
                if ($json['values'][$i][8] != '') {
                    $user->address = $json['values'][$i][8];
                }
                $user->country_id = 1;
                $user->save();
                $user->roles()->sync(Role::where('name', 'PARENT')->first());
                $user_call = new UserCall;
                if ($json['values'][$i][6] != '') {
                    $user_call->notes = $json['values'][$i][6];
                }
                if ($json['values'][$i][5] != '') {
                    $user_call->conversation_date = $json['values'][$i][5];
                }
                $user_call->calltype = 1;
                foreach ($status as $key => $value) {
                    if (strtolower($value) == strtolower($json['values'][$i][4])) {
                        $user_call->user_status_id = $key;
                    }
                }
                $user_call->user_id = $user->id;
                $user_call->created_at = $date;
                $user_call->updated_at = $date;

                $sales_info = new SalesInfo;
                foreach ($sources as $key => $value) {
                    if (strtolower($value) == strtolower($json['values'][$i][28])) {
                        $sales_info->source_id = $key;
                    }
                }
                foreach ($ads as $key => $value) {
                    if (strtolower($value) == strtolower($json['values'][$i][29])) {
                        $sales_info->ad_id = $key;
                    }
                }
                $sales_info->sales_manager_id = auth()->guard('admin')->user()->id;
                $sales_info->save();
                $user_call->sales_info_id = $sales_info->id;
                $user_call->save();
                $subscription = new Subscription;
                $subscription->pack_id = 52;
                $current_pack = '';
                if ($json['values'][$i][12] != '') {
                    switch ($json['values'][$i][12]) {
                        case 'T3':
                            $current_pack = 'اشتراك الثلاثي الثالث';
                            break;
                        case 'T2':
                            $current_pack = 'اشتراك الثلاثي الثاني';
                            break;
                        case 'AP':
                            $current_pack = 'اشتراك سنوي';
                            break;
                        case '2AP':
                            $current_pack = 'اشتراك السنة الحالية و السنة المقبلة';
                            break;
                        case 'T3+AP':
                            $current_pack = 'اشتراك الثلاثي الثالث';
                            break;
                        case 'T2+T3':
                            $current_pack = 'اشتراك الثلاثي الثاني و الثالث';
                            break;
                        case 'T1':
                            $current_pack = 'اشتراك الثلاثي الأول';
                            break;
                        default:
                            $current_pack = '';
                            break;
                    }
                }

                if ($json['values'][$i][10] != '') {
                    $child = new User;
                    $child->name = $json['values'][$i][10];
                    foreach ($levels as $key => $value) {
                        if (strtolower($value) == strtolower($json['values'][$i][11])) {
                            $child->level_id = $key;
                        }
                    }
                    $child->save();
                    $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                    $user->students()->sync($child->id);
                    $subscription->child_id = $child->id;

                    foreach ($packs as $pack) {
                        if ($pack->name == $current_pack && $pack->level_id == $child->level_id) {
                            $subscription->pack_id = $pack->id;
                        }
                    }
                }
                $subscription->user_id = $user->id;
                if ($json['values'][$i][18] != '') {
                    $token = Token::where('token', substr($json['values'][$i][18], 0, 9))->first();

                    if ($token != null) {
                        $token->user_id = $user->id;
                        $token->save();
                        $subscription->token_id = $token->id;
                    } else {
                        $token = new Token;
                        $token->token = substr($json['values'][$i][18], 0, 9);
                        $token->user_id = $user->id;
                        $token->value = 100;
                        $token->created_at = $date;
                        $token->updated_at = $date;
                        $token->validity_start = Carbon::now()->month(8);
                        $token->validity_end = Carbon::now()->addYear(1);
                        $token->save();
                        $subscription->token_id = $token->id;
                    }

                    if (substr($json['values'][$i][18], 10, 9) != '') {
                        $new_subscription = new Subscription;
                        $new_subscription->user_id = $user->id;
                        $new_subscription->child_id = $child->id;
                        $next_token = Token::where('token', substr($json['values'][$i][18], 10, 9))->first();

                        if ($next_token != null) {
                            $next_token->token = substr($json['values'][$i][18], 10, 19);
                            $next_token->user_id = $user->id;
                            $next_token->save();
                            $new_subscription->token_id = $next_token->id;
                        } else {
                            $next_token = new Token;
                            $next_token->token = substr($json['values'][$i][18], 10, 10);
                            $next_token->user_id = $user->id;
                            $next_token->created_at = $date;
                            $next_token->updated_at = $date;
                            $next_token->validity_start = Carbon::now()->month(8)->addYear(1);
                            $next_token->validity_end = Carbon::now()->addYear(2);
                            $next_token->value = 100;
                            $next_token->save();
                            $new_subscription->token_id = $next_token->id;
                        }

                        $new_subscription->pack_id = $subscription->pack_id;
                        $new_subscription->save();
                        $new_payment = null;
                        if ($json['values'][$i][14] != '') {
                            $new_payment = new Payment;
                            if ($json['values'][$i][21] != '') {
                                $new_payment->amount = $json['values'][$i][14];
                            }
                            $new_payment->payment_method_id = 3;
                            if ($json['values'][$i][21] != '') {
                                foreach ($methodes as $key => $value) {
                                    if (strtolower($value) == strtolower($json['values'][$i][21])) {
                                        $new_payment->payment_method_id = $key;
                                    }
                                }
                            }
                            $new_payment->user_id = $user->id;
                            $new_payment->subscription_id = $new_subscription->id;
                            $new_payment->save();
                            $history = new PaymentHistory;
                            $history->payment_id = $new_payment->id;
                            $history->payment_status_id = 1;

                            $history->save();
                        }
                        if ($json['values'][$i][17] != '') {
                            $new_delivery = new Delivery;
                            $new_delivery->delivery_status = $json['values'][$i][17];

                            if ($json['values'][$i][19] == 'TRUE') {
                                $new_delivery->delivery_status = 'recieved';
                            }
                            if ($json['values'][$i][20] != '') {
                                $new_delivery->delivery_date = date('Y-m-d', strtotime($json['values'][$i][20]));
                            }
                            if ($json['values'][$i][22] == 'TRUE') {
                                $new_delivery->double_delivery = 'true';
                            } else {
                                $new_delivery->double_delivery = 'false';
                            }

                            if ($json['values'][$i][15] == 'TRUE') {
                                $new_delivery->delivery_fees = 'true';
                            } else {
                                $new_delivery->delivery_fees = 'false';
                            }
                            if ($new_payment != null) {
                                $new_delivery->payment_id = $new_payment->id;
                            }
                            $new_delivery->subscription_id = $new_subscription->id;
                            $new_delivery->save();
                        }
                    }
                } else {
                    // test on pack type
                    $generated_token = new Token;
                    $generated_token->token = app('App\Http\Controllers\Admin\TokenController')->generateToken();

                    $generated_token->user_id = $user->id;
                    $generated_token->value = 100;
                    $generated_token->created_at = $date;
                    $generated_token->updated_at = $date;
                    $generated_token->validity_start = Carbon::now()->month(8);
                    $generated_token->validity_end = Carbon::now()->addYear(1);
                    $generated_token->save();
                    $subscription->token_id = $generated_token->id;

                    // pack type next create other subscription
                    if ($json['values'][$i][12] == '2AP') {
                        $new_subscription = new Subscription;
                        $new_subscription->user_id = $user->id;
                        $new_subscription->child_id = $child->id;
                        $next_token = new Token;
                        $next_token->token = app('App\Http\Controllers\Admin\TokenController')->generateToken() . '*';
                        $next_token->user_id = $user->id;
                        $next_token->created_at = $date;
                        $next_token->updated_at = $date;
                        $next_token->validity_start = Carbon::now()->month(8)->addYear(1);
                        $next_token->validity_end = Carbon::now()->addYear(2);
                        $next_token->value = 100;
                        $next_token->save();
                        $new_subscription->token_id = $next_token->id;

                        $new_subscription->pack_id = $subscription->pack_id;
                        $new_subscription->save();
                        $new_payment = null;
                        if ($json['values'][$i][14] != '') {
                            $new_payment = new Payment;
                            if ($json['values'][$i][14] != '') {
                                $new_payment->amount = $json['values'][$i][14];
                            }
                            $new_payment->payment_method_id = 3;
                            if ($json['values'][$i][21] != '') {
                                foreach ($methodes as $key => $value) {
                                    if (strtolower($value) == strtolower($json['values'][$i][21])) {
                                        $new_payment->payment_method_id = $key;
                                    }
                                }
                            }
                            $new_payment->user_id = $user->id;
                            $new_payment->subscription_id = $new_subscription->id;
                            $new_payment->save();
                            $history = new PaymentHistory;
                            $history->payment_id = $new_payment->id;
                            $history->payment_status_id = 1;

                            $history->save();
                        }
                        if ($json['values'][$i][17] != '') {
                            $new_delivery = new Delivery;
                            $new_delivery->delivery_status = $json['values'][$i][17];

                            if ($json['values'][$i][19] == 'TRUE') {
                                $new_delivery->delivery_status = 'recieved';
                            }
                            if ($json['values'][$i][20] != '') {
                                $new_delivery->delivery_date = date('Y-m-d', strtotime($json['values'][$i][20]));
                            }
                            if ($json['values'][$i][22] == 'TRUE') {
                                $new_delivery->double_delivery = 'true';
                            } else {
                                $new_delivery->double_delivery = 'false';
                            }

                            if ($json['values'][$i][15] == 'TRUE') {
                                $new_delivery->delivery_fees = 'true';
                            } else {
                                $new_delivery->delivery_fees = 'false';
                            }
                            if ($new_payment != null) {
                                $new_delivery->payment_id = $new_payment->id;
                            }
                            $new_delivery->subscription_id = $new_subscription->id;

                            $new_delivery->save();
                        }
                    }
                }
                $subscription->save();
                $payment = null;
                if ($json['values'][$i][14] != '') {
                    $payment = new Payment;
                    if ($json['values'][$i][14] != '') {
                        $payment->amount = $json['values'][$i][14];
                    }
                    $payment->payment_method_id = 3;
                    if ($json['values'][$i][21] != '') {
                        foreach ($methodes as $key => $value) {
                            if (strtolower($value) == strtolower($json['values'][$i][21])) {
                                $payment->payment_method_id = $key;
                            }
                        }
                    }
                    $payment->user_id = $user->id;
                    $payment->subscription_id = $subscription->id;
                    $payment->save();
                    $history = new PaymentHistory;
                    $history->payment_id = $payment->id;
                    $history->payment_status_id = 1;

                    $history->save();
                }
                if ($json['values'][$i][17] != '') {
                    $delivery = new Delivery;
                    $delivery->delivery_status = $json['values'][$i][17];

                    if ($json['values'][$i][19] == 'TRUE') {
                        $delivery->delivery_status = 'recieved';
                    }
                    if ($json['values'][$i][20] != '') {
                        $delivery->delivery_date = date('Y-m-d', strtotime($json['values'][$i][20]));
                    }
                    if ($json['values'][$i][22] == 'TRUE') {
                        $delivery->double_delivery = 'true';
                    } else {
                        $delivery->double_delivery = 'false';
                    }

                    if ($json['values'][$i][15] == 'TRUE') {
                        $delivery->delivery_fees = 'true';
                    } else {
                        $delivery->delivery_fees = 'false';
                    }
                    if ($payment != null) {
                        $delivery->payment_id = $payment->id;
                    }
                    $delivery->subscription_id = $subscription->id;
                    $delivery->save();
                }
            }
        }
        // return view('admin.logistics.create.index');
    }
    public function destroy($id)
    {
        Subscription::destroy($id);
        session()->flash('success', 'Subscription deleted successfully');
        return view('admin.logistics.create.index');
    }
    public function insertSession($param)
    {
        $p = json_decode($param);
        // dd($p);
        session()->flash('err', $p);
        // request()->session()->put('err', $p);
    }
}
