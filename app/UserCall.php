<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\SalesInfo as SalesResource;

class UserCall extends Model
{
    protected $fillable = ["actual_status", "notes",  "conversation_date","SMS_id","sales_info_id", "user_id"];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function salesInfo()
    {
        return $this->belongsTo('App\SalesInfo');
        // $sales = SalesInfo::all();
        // return SalesResource::collection($sales);
    }
    public function sms()
    {
        return $this->hasMany('App\Sms');
    }
    
    

}