<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function questionGroup()
    {
        return $this->belongsTo('App\QuestionGroup');
    }
}