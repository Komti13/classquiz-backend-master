<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function delegation()
    {
        return $this->belongsTo('App\Delegation');
    }

    public function governorate()
    {
        return $this->belongsTo('App\Governorate');
    }
}
