<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\Subscription;
use App\Chapter;
use App\PackPromotion;
use App\User;
use App\Ad;
use App\Level;
use App\Role;
use App\School;
use App\Country;
use App\Delivery;
use App\Feedback;
use App\Governorate;
use App\Pack;
use App\Payment;
use App\PaymentHistory;
use App\Source;
use App\PaymentMethod;
use App\PaymentStatus;
use App\SalesInfo;
use App\Sms;
use App\Support;
use App\Token;
use App\UserCall;
use App\UserStatus;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Util\Json;

class LogisticController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $calls = Subscription::orderBy('id', 'DESC')->with('user.usercalls.userStatus', 'user.usercalls.salesInfo.source', 'user.usercalls.salesInfo.ad', 'pack.packType', 'pack.level', 'payment.paymentMethod', 'payment.delivery');
            return datatables()->of($calls)->toJson();
        }

        return view('admin.logistics.index');
    }
    public function create()
    {
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $sources = Source::pluck('type', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('name', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id');
        $cities = Governorate::pluck('name', 'id');
        return view('admin.logistics.user_form', compact('schools', 'countries', 'sources', 'ads', 'methodes', 'payStatus', 'packs', 'status', 'cities'));
    }

    public function store($source)
    {
        $schools = School::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        $sources = Source::pluck('type', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('name', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id', 'level_id', 'price');
        $cities = Governorate::pluck('name', 'id');
        $allpacks = Pack::select([
            'id', 'name', 'level_id', 'price'
        ])->get();
        $promos = PackPromotion::select(
            [
                'id',
                'pack_id',
                'value'
            ]
        )->get();
        $nbchild = 0;
        if ($source == 'first') {
            // request()->validate([
            //     'name' => 'required|string',
            //     'phone' => 'required|string|unique:users',
            //     'address' => 'required|string',
            //     'children' => 'required|min:0',
            // ]);
            $nbchild = request('children');
            Cookie::queue('nbchildren', $nbchild, 10);

            return view('admin.logistics.call_form', compact('status'));
        }
        if ($source == 'second') {
            if (request('calltype') == 'Sale') {
                $nbchild = Cookie::get('nbchildren');
                return view('admin.logistics.personal_form', compact('schools', 'countries', 'levels', 'sources', 'ads', 'methodes', 'payStatus', 'packs', 'status', 'nbchild', 'promos', 'allpacks'));
            } else if (request('calltype') == 'Feedback') {
                return view('admin.logistics.feedback');
            } else {
                return view('admin.logistics.techSupport');
            }
        }
        if ($source == 'third') {
            $user = new User;
            $user->name = request('name');
            if (request('phone2')) {
                $user->phone = request('phone') . " /" . request('phone2');
            } else {
                $user->phone = request('phone');
            }
            $user->address = request('address') . " " . $cities[request('city_id')];
            $user->country_id = request('country_id');
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
            if (trim(request('sms_text'), ' ') != '' && trim(request('type'), ' ') != '') {
                $sms->text = request('sms_text');
                $sms->type = request('type');
                $userCall->sms_sent = true;
            }
            $sms->save();
            $userCall->sms_id = $sms->id;
            $userCall->notes = request('notes');
            $userCall->sales_info_id = $sales_info->id;
            $userCall->user_id = $user->id;
            $userCall->user_status_id = request('status');
            $userCall->call_type = request('calltype');
            $userCall->conversation_date = request('call_date');
            $userCall->save();
            $nbchild = Cookie::get('nbchildren');
            if ($nbchild > 0) {
                for ($i = 0; $i < $nbchild; $i++) {
                    $child = new User;
                    $child->name = request('name' . $i);
                    $child->level_id = request('level_id' . $i);
                    $child->save();
                    $child->roles()->sync(Role::where('name', 'STUDENT')->first());
                    $user->students()->sync($child->id);
                    $current_token = new Token;
                    $current_token->token = request('current' . $i);
                    $current_token->validity_start = Carbon::now();
                    $current_token->validity_end = Carbon::now()->addYears(1);
                    $current_token->value = request('promo_price' . $i);
                    $current_token->user_id = $user->id;
                    $current_token->save();
                    if (request('next' . $i) != null) {
                        $next_token = new Token;
                        $next_token->token = request('next' . $i);
                        $next_token->validity_start = Carbon::now()->addYears(1);
                        $next_token->validity_end = Carbon::now()->addYears(2);
                        $next_token->value = request('promo_price' . $i);
                        $next_token->user_id = $user->id;
                        $next_token->save();
                        $subscription = new Subscription;
                        $subscription->pack_id = request('pack' . $i);
                        $subscription->user_id = $user->id;
                        $subscription->token_id = $next_token->id;
                        $subscription->save();
                    }
                    $subscription = new Subscription;
                    $subscription->pack_id = request('pack' . $i);
                    $subscription->user_id = $user->id;
                    $subscription->token_id = $current_token->id;
                    $subscription->save();

                    if (request('payment') > 0) {
                        $payment = new Payment;
                        $payment->amount = request('amount');
                        $payment->user_id = $user->id;
                        $payment->payment_method_id = request('payment');
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
                        $delivery->save();
                    }
                }
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
            $user->address = request('address') . " " . request('city_id');
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
            $user->address = request('address') . " " . request('city_id');
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
    public function logistics()
    {
        return view('admin.logistics.index');
    }
}
