<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackPromotion extends Model
{
    use SoftDeletes;

    public function pack(){
        return $this->belongsTo('App\Pack');
    }
}
