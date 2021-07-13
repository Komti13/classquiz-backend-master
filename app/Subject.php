<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    public function chapters()
    {
        return $this->hasMany('App\Chapter');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Level');
    }

    public function levelChapters($levelId)
    {
        return \App\Chapter::where('subject_id', $this->id)->whereHas('levels', function ($q) use ($levelId) {
            $q->where('id', $levelId);
        })->get();
    }
}
