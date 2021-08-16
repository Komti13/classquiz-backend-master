<?php

namespace App\Http\Controllers\Admin;

use App\Ad;
use App\Country;
use App\Delivery;
use App\Feedback;
use App\Governorate;
use App\Http\Controllers\Controller;
use App\Http\Resources\Subscription as SubResource;
use App\Level;
use App\Pack;
use App\PackPromotion;
use App\Payment;
use App\PaymentHistory;
use App\PaymentMethod;
use App\PaymentStatus;
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
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use PDF;

class LogisticController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $calls = Subscription::orderBy('id', 'DESC')->with('user.usercalls.userStatus', 'user.usercalls.salesInfo.source', 'user.usercalls.salesInfo.ad', 'pack.packType', 'pack.level', 'payment.paymentMethod', 'delivery', 'child');
            return datatables()->of($calls)->toJson();
        }

        return view('admin.logistics.create.index');
    }
    public function create()
    {
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $sources = Source::pluck('source', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('paymethod', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id');
        $cities = Governorate::pluck('name', 'id');
        return view('admin.logistics.create.user_form', compact('schools', 'countries', 'sources', 'ads', 'methodes', 'payStatus', 'packs', 'status', 'cities'));
    }

    public function editform($id)
    {
        return view('admin.logistics.update.form_template', compact('id'));
    }
    public function edit($id)
    {
        // dd($id);
        //get user from data base with the subscription id
        $subs = Subscription::where('id', $id)->get();
        $sub = SubResource::collection($subs);
        $user = $sub[0]->user;
        $countries = Country::pluck('name', 'id');
        $cities = Governorate::pluck('name', 'id');
        return view('admin.logistics.update.user_form', compact('countries', 'cities', 'user', 'id'));
    }
    public function update($id, $source)
    {

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
            $user_call = $sub[0]->user->usercalls;
            $status = UserStatus::pluck('name', 'id');
            $nbchild = request('nb_children');
            Cookie::queue('nbchildren', $nbchild, 10);
            return view('admin.logistics.update.call_form', compact('status', 'user_call', 'id'));
        }
        if ($source == 'second') {
            $source = $sub[0]->user->usercalls->salesInfo->source;
            $payment = $sub[0]->payment;
            $token = $sub[0]->token;
            $s = $sub[0];
            $sms = $sub[0]->user->usercalls->sms;

            $schools = School::pluck('name', 'id');
            $countries = Country::pluck('name', 'id');
            $levels = Level::pluck('name', 'id');
            $sources = Source::pluck('source', 'id');
            $ads = Ad::pluck('type', 'id');
            $methodes = PaymentMethod::pluck('paymethod', 'id');
            $payStatus = PaymentStatus::pluck('name', 'id');
            $packs = Pack::pluck('name', 'id', 'level_id', 'price');
            $cities = Governorate::pluck('name', 'id');
            $allpacks = Pack::select([
                'id', 'name', 'level_id', 'price',
            ])->get();
            return view('admin.logistics.update.personal_form', compact('schools', 'countries', 'levels', 'sources', 'ads', 'methodes', 'payStatus', 'packs', 'promos', 'allpacks', 'id', 'payment', 'sms', 's'));
        }
        if ($source == 'third') {
            // dd(request()->all());
            $user = User::findOrFail($sub[0]->user->id);
            $user->name = request('name');
            $user->phone = request('phone');
            $user->phone2 = request('phone2');
            if (request('city_id') != null) {
                $user->address = request('address') . " " . $cities[request('city_id')];
                $user->city_id = request('city_id');
            }
            if (request('country_id') != null) {
                $user->country_id = request('country_id');
            }
            $user->nb_children = request('nb_children');
            $user->save();
            $sales_info = SalesInfo::findOrFail($sub[0]->user->usercalls->salesInfo->id);
            $sales_info->source_id = request('source');
            $sales_info->ad_id = request('ad');
            $sales_info->sales_manager_id = auth()->guard('admin')->user()->id;
            $sales_info->save();
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
            $userCall->user_status_id = request('status');
            $userCall->calltype = request('calltype');
            $userCall->conversation_date = request('conversation_date');
            $userCall->save();
            if (request('namechild') != null) {
                if (trim(request('namechild'), ' ') != '') {
                    if ($sub[0]->child != null) {
                        $child = User::findOrFail($sub[0]->child->id);
                        $child->name = request('namechild');
                        $child->level_id = request('level_id');
                        $child->save();
                    } else {
                        $child = new User;
                        $child->name = request('namechild');
                        $child->level_id = request('level_id');
                        $child->save();
                        $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                        $user->students()->sync($child->id);
                    }
                }
            }
            // update the current subscription
            $subscription = Subscription::findOrFail($sub[0]->id);
            if (request('pack') != null) {
                if (request('pack') != '---') {
                    $subscription->pack_id = request('pack');
                } else {
                    $subscription->pack_id = 52;
                }
            }
            $subscription->save();
            // delete current subscription if it has no children and if a new child is added because already a new subscription is created
            if (request('pack') == null && trim(request('newchildname'), ' ') != '') {
                Subscription::destroy($subscription->id);
            }
            $payment = null;
            if (request('paymethod') > 0) {
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
                $history->payment_status_id = request('paystatus');
                $history->save();
            }
            if (request('delivery_date') != "") {
                $delivery = Delivery::where('subscription_id', $subscription->id)->first();
                if ($delivery == null) {
                    $delivery = new Delivery;
                }
                $delivery->delivery_date = request('delivery_date');
                $delivery->delivery_status = request('delstatus');
                $delivery->delivery_fees = request('fees');
                if ($payment != null) {
                    $delivery->payment_id = $payment->id;
                }
                $delivery->subscription_id = $subscription->id;
                $delivery->save();
            }
            // new Child
            if (trim(request('newchildname'), ' ') != '') {
                $user = User::findOrFail($sub[0]->user->id);
                if ($user->nb_children == null) {
                    $user->nb_children = 0;
                }
                $user->nb_children = $user->nb_children + 1;
                $user->save();
                $child = new User;
                $child->name = request('newchildname');
                $child->level_id = request('level_id1');
                $child->save();
                $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                $user->students()->sync($child->id);

                $subscription = new Subscription;
                if (request('pack1') != '---') {
                    $subscription->pack_id = request('pack1');
                } else {
                    $subscription->pack_id = 52;
                }

                $subscription->user_id = $user->id;
                $subscription->child_id = $child->id;
                $token = new Token;
                $token->token = request('current1');
                $token->validity_start = Carbon::now();
                $token->validity_end = Carbon::now()->addYears(1);
                // 
                foreach ($promos as $promo) {
                    if ($promo->pack_id == (int)request('pack1')) {
                        $token->value = $promo->value;
                    }
                }
                if ($token->value == null) {
                    foreach ($allpacks as $pack) {
                        if ($pack->id == (int)request('pack1')) {
                            $token->value = $pack->price;
                        }
                    }
                }
                $token->user_id = $user->id;
                $token->save();
                $subscription->token_id = $token->id;
                $subscription->save();
                if (request('paymethod') > 0) {
                    $payment = new Payment;
                    $payment->amount = request('amount');
                    $payment->user_id = $user->id;
                    $payment->payment_method_id = request('paymethod');
                    $payment->subscription_id = $subscription->id;
                    $payment->save();
                    $history = new PaymentHistory;
                    $history->payment_id = $payment->id;
                    if (request('paystatus') > 0) {
                        $history->payment_status_id = request('paystatus');
                    }
                    $history->save();
                }
                if (request('next1') != '') {
                    $subscription = new Subscription;
                    if (request('pack1') != '---') {
                        $subscription->pack_id = request('pack1');
                    } else {
                        $subscription->pack_id = 52;
                    }
                    $subscription->user_id = $user->id;
                    $subscription->child_id = $child->id;
                    $token = new Token;
                    $token->token = request('next1') . '*';
                    $token->validity_start = Carbon::now()->addYears(1);
                    $token->validity_end = Carbon::now()->addYears(2);
                    foreach ($promos as $promo) {
                        if ($promo->pack_id == (int)request('pack1')) {
                            $token->value = $promo->value;
                        }
                    }
                    if ($token->value == null) {
                        foreach ($allpacks as $pack) {
                            if ($pack->id == (int)request('pack1')) {
                                $token->value = $pack->price;
                            }
                        }
                    }
                    $token->user_id = $user->id;
                    $token->save();
                    $subscription->token_id = $token->id;
                    $subscription->save();
                    if (request('paymethod') > 0) {
                        $payment = new Payment;
                        $payment->amount = request('amount');
                        $payment->user_id = $user->id;
                        $payment->payment_method_id = request('paymethod');
                        $payment->subscription_id = $subscription->id;
                        $payment->save();
                        $history = new PaymentHistory;
                        $history->payment_id = $payment->id;
                        if (request('paystatus') > 0) {
                            $history->payment_status_id = request('paystatus');
                        }
                        $history->save();
                    }
                }
            }
        }
    }

    public function store($source)
    {
        $schools = School::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        $sources = Source::pluck('source', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('paymethod', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id', 'level_id', 'price');
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
        $nbchild = 0;
        if ($source == 'first') {
            $nbchild = request('nb_children');
            Cookie::queue('nbchildren', $nbchild, 10);

            return view('admin.logistics.create.call_form', compact('status'));
        }
        if ($source == 'second') {
            if (request('calltype') == 'Sale') {
                $nbchild = Cookie::get('nbchildren');
                return view('admin.logistics.create.personal_form', compact('schools', 'countries', 'levels', 'sources', 'ads', 'methodes', 'payStatus', 'packs', 'status', 'nbchild', 'promos', 'allpacks'));
            } else if (request('calltype') == 'Feedback') {
                return view('admin.logistics.create.feedback');
            } else {
                return view('admin.logistics.create.techSupport');
            }
        }
        if ($source == 'third') {
            $nbchild = Cookie::get('nbchildren');
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
            if (request('country_id') != null) {
                $user->country_id = request('country_id');
            }
            if ($nbchild > 0) {
                $user->nb_children = $nbchild;
            }

            $user->save();
            $user->roles()->sync(Role::where('name', 'PARENT')->first());
            $sales_info = new SalesInfo;
            if (request('source') > 0) {
                $sales_info->source_id = request('source');
            }
            if (request('ad') > 0) {
                $sales_info->ad_id = request('ad');
            }
            $sales_info->sales_manager_id = auth()->guard('admin')->user()->id;
            $sales_info->save();
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
            $userCall->calltype = request('calltype');
            $userCall->conversation_date = request('call_date');
            $userCall->save();

            $test = true;
            if ($nbchild > 0) {
                for ($i = 0; $i < $nbchild; $i++) {
                    $child = null;
                    $current_token = null;
                    if (request('name' . $i)) {
                        $test = true;
                        $child = new User;
                        $child->name = request('name' . $i);
                        if (request('level_id' . $i)) {
                            $child->level_id = request('level_id' . $i);
                        }

                        $child->save();
                        $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                        $user->students()->sync($child->id);

                        $current_token = new Token;
                        $current_token->token = request('current' . $i);
                        $current_token->validity_start = Carbon::now();
                        $current_token->validity_end = Carbon::now()->addYears(1);
                        foreach ($promos as $promo) {
                            if ($promo->pack_id == (int)request('pack' . $i)) {
                                $current_token->value = $promo->value;
                            }
                        }
                        if ($current_token->value == null) {
                            foreach ($allpacks as $pack) {
                                if ($pack->id == (int)request('pack' . $i)) {
                                    $current_token->value = $pack->price;
                                }
                            }
                        }
                        $current_token->user_id = $user->id;
                        $current_token->save();
                        if (request('next' . $i) != null) {
                            $next_token = new Token;
                            $next_token->token = request('next' . $i) . '*';
                            $next_token->validity_start = Carbon::now()->addYears(1);
                            $next_token->validity_end = Carbon::now()->addYears(2);
                            $next_token->value = $current_token->value;
                            $next_token->user_id = $user->id;
                            $next_token->save();
                            $subscription = new Subscription;
                            if (request('pack' . $i) != '---') {
                                $subscription->pack_id = request('pack' . $i);
                            } else {
                                $subscription->pack_id = 52;
                            }
                            $subscription->user_id = $user->id;
                            $subscription->child_id = $child->id;
                            $subscription->token_id = $next_token->id;
                            $subscription->save();
                            $payment = null;
                            if (request('paymethod') > 0) {
                                $payment = new Payment;
                                $payment->amount = request('amount');
                                $payment->user_id = $user->id;
                                $payment->payment_method_id = request('paymethod');
                                $payment->subscription_id = $subscription->id;
                                $payment->save();
                                $history = new PaymentHistory;
                                $history->payment_id = $payment->id;
                                if (request('paystatus') > 0) {
                                    $history->payment_status_id = request('paystatus');
                                }
                                $history->save();
                            }
                            if (request('delivery_date') != "") {
                                $delivery = new Delivery;
                                $delivery->delivery_date = request('delivery_date');
                                $delivery->delivery_status = request('delstatus');
                                $delivery->delivery_fees = request('fees');
                                if ($payment != null) {
                                    $delivery->payment_id = $payment->id;
                                }
                                $delivery->subscription_id = $subscription->id;

                                $delivery->save();
                            }
                        }
                    }
                    if ($test) {
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

                        $payment = new Payment;
                        if (request('paymethod') > 0) {
                            $payment->amount = request('amount');
                            $payment->user_id = $user->id;
                            $payment->payment_method_id = request('paymethod');
                            $payment->subscription_id = $subscription->id;
                            $payment->save();
                            $history = new PaymentHistory;
                            $history->payment_id = $payment->id;
                            if (request('paystatus') > 0) {
                                $history->payment_status_id = request('paystatus');
                            }
                            $history->save();
                        }

                        if (request('delivery_date') != "") {
                            $delivery = new Delivery;
                            $delivery->delivery_date = request('delivery_date');
                            $delivery->delivery_status = request('delstatus');
                            $delivery->delivery_fees = request('fees');
                            $delivery->payment_id = $payment->id;
                            $delivery->subscription_id = $subscription->id;
                            $delivery->save();
                        }
                        $test = false;
                    }
                }
            } else {
                $subscription = new Subscription;
                $subscription->user_id = $user->id;
                $subscription->pack_id = 52;
                $subscription->save();
            }
        } else
        if ($source == 'feedback') {
            $user = new User;
            $user->name = request('name');
            if (request('phone2')) {
                $user->phone = request('phone') . " /" . request('phone2');
            } else {
                $user->phone = request('phone');
            }
            $user->address = request('address') . "-" . request('city_id');
            $user->country_id = request('country_id');
            $user->save();
            $userCall = new UserCall;
            $userCall->notes = request('notes');
            $userCall->user_id = $user->id;
            $userCall->user_status_id = request('status');
            $userCall->call_type = request('calltype');
            $userCall->conversation_date = request('call_date');
            $userCall->save();
            $feedback = new Feedback;
            $feedback->description = request('feedback');
            $feedback->user_id = $user->id;
            $feedback->user_call_id = $userCall->id;
            $feedback->save();
        } else
        if ($source == 'support') {
            $user = new User;
            $user->name = request('name');
            if (request('phone2')) {
                $user->phone = request('phone') . " /" . request('phone2');
            } else {
                $user->phone = request('phone');
            }
            $user->address = request('address') . "-" . request('city_id');
            $user->country_id = request('country_id');
            $user->save();
            $userCall = new UserCall;
            $userCall->notes = request('notes');
            $userCall->user_id = $user->id;
            $userCall->user_status_id = request('status');
            $userCall->call_type = request('calltype');
            $userCall->conversation_date = request('call_date');
            $userCall->save();
            $support = new Support;
            $support->problem = request('problem');
            $support->user_id = $user->id;
            $support->user_call_id = $userCall->id;
            $support->save();
        }
    }
    public function createPDF($type, $ids)
    {
        if ($type == 'code') {
            $pages=[];
            for ($i=0; $i <count(explode(',', $ids)) ; $i++) { 
                $calls = Subscription::where('id', explode(',', $ids)[$i])->get();
                $call = SubResource::collection($calls);
                $userid = $call[0]->user->id;
                $token = $call[0]->token->token;
                $pages[$i]['id']=$userid;
                $pages[$i]['token']=$token;

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
            $calls = Subscription::where('id', $ids)->get();
            $call = SubResource::collection($calls);
            $userid = $call[0]->user->id;
            $pack = $call[0]->pack->name;
            $level = $call[0]->pack->level->name;
            $date = $call[0]->created_at;
            $price = $call[0]->pack->price;
            $amount = $call[0]->payment->amount;
            $fileName = 'ticket' . $userid . '.' . 'pdf';
            $data = ['pack' => $pack, 'id' => $userid, 'level' => $level, 'date' => $date, 'price' => $price, 'amount' => $amount];
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
            // if ($today == $date) {
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
                    $token->validity_start = Carbon::now();
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
                        $next_token->validity_start = Carbon::now()->addYear(1);
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
                $generated_token->validity_start = Carbon::now();
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
                    $next_token->validity_start = Carbon::now()->addYear(1);
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
            // }
        }
        // return view('admin.logistics.create.index');
    }
    public function destroy($id)
    {
        Subscription::destroy($id);
        session()->flash('success', 'Subscription deleted successfully');
        return view('admin.logistics.create.index');
    }
}
