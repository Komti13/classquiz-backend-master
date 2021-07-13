<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizoItem extends Model
{
    public function quizos()
    {
        return $this->belongsToMany('App\Quizo');
    }
}
