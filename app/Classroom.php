<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function students()
    {
        return $this->belongsToMany('App\User')->whereHas('roles', function ($q) {
            $q->where('name', 'STUDENT');
        })->get();
    }

    public function teachers()
    {
        return $this->belongsToMany('App\User')->withPivot('subject_id')->whereHas('roles', function ($q) {
            $q->where('name', 'TEACHER');
        })->get();
    }
}
