<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ["score", "time", "user_id", "question_group_id", "is_done", "nb_mistakes"];


    public function questionGroup()
    {
        return $this->belongsTo('App\QuestionGroup');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
