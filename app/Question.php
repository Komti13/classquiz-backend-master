<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    public function questionGroup()
    {
        return $this->belongsTo('App\QuestionGroup');
    }

    public function chapter()
    {
        return $this->belongsTo('App\Chapter');
    }

    public function template()
    {
        return $this->belongsTo('App\Template');
    }

    public function QuizFieldData()
    {
        return $this->hasMany('App\QuizFieldDatum');
    }
}
