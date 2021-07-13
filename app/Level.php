<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;

    public function subjects()
    {
        return $this->belongsToMany('App\Subject');
    }

    public function chapters()
    {
        return $this->belongsToMany('App\Chapter')
            ->withPivot('order')
            ->orderBy('order', 'desc');
    }
}
