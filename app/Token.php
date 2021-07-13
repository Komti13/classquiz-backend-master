<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;

    protected $appends = ['is_valid'];

    public function getIsValidAttribute()
    {
        return $this->validity_start <= Carbon::now() && $this->validity_end >= Carbon::now();
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function subscription(){
        return $this->hasOne('App\Subscription');
    }
}
