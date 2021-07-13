<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInfo extends Model
{
    protected $fillable = ["source", "salesManager", "ad"];

    public function source()
    {
        return $this->belongsTo('App\Source');
    }
    public function salesManager()
    {
        return $this->belongsTo('App\Admin');
    }
    public function ad()
    {
        return $this->belongsTo('App\Ad');
    }
    public function userCall()
    {
        return $this->belongsTo('App\UserCall');
    }
    
  
    
}