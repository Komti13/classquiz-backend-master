<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\Subscription;
use App\Chapter;
use App\PackType;
use App\User;
use App\Ad;
use App\Level;
use App\Role;
use App\School;
use App\Country;
use App\Pack;
use App\Source;
use App\PaymentMethod;
use App\PaymentStatus;
use App\Sms;
use App\UserStatus;
use Carbon\Carbon;



class LogisticController extends Controller
{
    public function index()
    {
        if (request()->isXmlHttpRequest()) {
            $calls = Subscription::with('user.level', 'user.usercalls','user.usercalls.userStatus', 'user.usercalls.salesInfo', 'user.usercalls.salesInfo.salesManager', 'user.usercalls.salesInfo.source', 'user.usercalls.salesInfo.ad', 'payment', 'payment.paymentMethod', 'payment.delivery');

            return datatables()->eloquent($calls)->toJson();
        }

        return view('admin.logistics.index');
    }
    public function create()
    {

        // $packTypes = PackType::pluck('name', 'id');
        // $levels = Level::all();
        // $chapters = Chapter::pluck('name', 'id');
        // $role = Role::where('name', $role)->first();
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        // $levels = Level::pluck('name', 'id');
        $sources = Source::pluck('type', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('name', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id');
        
       
            return view('admin.logistics.create', compact('schools', 'countries', 'sources','ads','methodes','payStatus','packs','status'));

        
    }

    public function store($source)
    {
        // request()->validate([
        //     'name' => 'required|string',
        //     'phone' => 'string|unique:users',
        //     'address' => 'nullable|string',
        //     'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'school' => 'nullable|string',
        //     'school_id' => 'exists:schools,id',
        //     'country_id' => 'exists:countries,id',
        //     'level_id' => 'exists:levels,id',
        //     'email' => 'nullable|string|email|unique:users',
        //     'password' => 'required|string|confirmed',
        //     'enabled' => 'boolean',
        // ]);
        // $user = new User;
        // $user->name = request('name');
        // $user->phone = request('phone');
        // $user->address = request('address');
        // $user->school = request('school');
        // $user->school_id = request('school_id');
        // $user->level_id = request('level_id');
        // $user->country_id = request('country_id');
        // $user->email = strtolower(request('email'));
        // $user->password = bcrypt(request('password'));
        // if (request('image')) {
        //     $imageName = time() . '.' . request('image')->getClientOriginalExtension();
        //     request('image')->move(public_path('uploads/user/'), $imageName);
        //     $user->image = $imageName;
        // }

        // $user->enabled = request('enabled') ? 1 : 0;
        // $user->active = true;


        // $user->save();

        // $role = Role::where('name', $role)->first();
        // $user->roles()->attach($role->id);


        // return redirect()->route('users.index', ['role' => $role->name])
        //     ->with('success', 'User created successfully');
        // $sms=new Sms;
        // $sms->text=request('sms_text');
        // $sms->type=request('type');
        // $sms->save();
        // return $sms->id;
        $schools = School::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');
        $levels = Level::pluck('name', 'id');
        $sources = Source::pluck('type', 'id');
        $ads = Ad::pluck('type', 'id');
        $methodes = PaymentMethod::pluck('name', 'id');
        $payStatus = PaymentStatus::pluck('name', 'id');
        $status = UserStatus::pluck('name', 'id');
        $packs = Pack::pluck('name', 'id','level_id');
        $nbchild=0;
        if ($source=='first') {
            // request()->validate([
            //     'name' => 'required|string',
            //     'phone' => 'required|string|unique:users',
            //     'address' => 'required|string',
            //     'children' => 'required|min:0',
            // ]);
           $nbchild=request('children');
           Cookie::queue('nbchildren', $nbchild, 10);
            return view('admin.logistics.next1', compact('status'));

        }
        if ($source=='second') {
            if (request('calltype')==0) {
                $nbchild =Cookie::get('nbchildren');
                return view('admin.logistics.next2', compact('schools', 'countries','levels', 'sources','ads','methodes','payStatus','packs','status','nbchild'));
    
            } else if(request('calltype')==1){
                return view('admin.logistics.feedback');
    
            }else{
                return view('admin.logistics.techSupport');
            }
            
           
        }
        if ($source=='third') {
            return $_REQUEST;
            // return view('admin.logistics.next2', compact('schools', 'countries','levels', 'sources','ads','methodes','payStatus','packs','status','nbchild'));

        }
         else { 
            
            return view('admin.logistics.create', compact('countries'));

        }

    }
    
    public function logistics()
    {
        return view('admin.logistics.index');
    }
}