<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    public function chapter()
    {
        return $this->belongsTo('App\Chapter');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

