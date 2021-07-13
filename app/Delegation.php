<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    public function governorate()
    {
        return $this->belongsTo('App\Governorate');
    }
}
