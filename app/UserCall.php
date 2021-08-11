<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\SalesInfo as SalesResource;

class UserCall extends Model
{
    protected $fillable = ["user_status_id", "notes",  "conversation_date","sms_id","sms_sent","sales_info_id", "user_id","call_type"];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function userStatus()
    {
        return $this->belongsTo('App\UserStatus');
    }
    public function salesInfo()
    {
        return $this->belongsTo('App\SalesInfo');
        // $sales = SalesInfo::all();
        // return SalesResource::collection($sales);
    }
    public function sms()
    {
        return $this->belongsTo('App\Sms');
    }
    
    

}