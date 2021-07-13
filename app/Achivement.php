<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achivement extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }
}
