<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quizo extends Model
{
    public function quizoItems()
    {
        return $this->belongsToMany('App\QuizoItem');
    }
}
