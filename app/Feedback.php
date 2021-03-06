<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ["description", "user_id","userCall_id"];
    
    public function userCalls()
    {
        return $this->belongsTo('App\UserCalls');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
}