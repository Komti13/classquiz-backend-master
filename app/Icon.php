<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    public function atlas()
    {
        return $this->belongsTo('App\Atlas');
    }
}
