<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $fillable = ["text","status_id","type"];

    public function userCall()
    {
        return $this->belongsTo('App\UserCall');
    }

    
}