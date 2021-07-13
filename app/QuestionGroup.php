<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionGroup extends Model
{
    use SoftDeletes;

    public function packs()
    {
        return $this->belongsToMany('App\Pack');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Chapter');
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function homeworks()
    {
        return $this->hasMany('App\Homework');
    }
}
