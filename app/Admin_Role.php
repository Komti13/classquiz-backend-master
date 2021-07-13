<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin_Role extends Model
{
    protected $fillable = ["name"];

    public function admin()
    {
        return $this->belongsTo('App\Admin');
    }
}