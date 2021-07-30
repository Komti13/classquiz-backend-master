<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = ["problem", "user_id","userCall_id"];
    
    public function userCalls()
    {
        return $this->belongsTo('App\UserCalls');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
