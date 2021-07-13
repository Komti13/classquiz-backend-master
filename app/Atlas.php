<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atlas extends Model
{
    public function icons()
    {
        return $this->hasMany('App\Icon');
    }
}
