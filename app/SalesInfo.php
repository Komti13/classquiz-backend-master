<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesInfo extends Model
{
    protected $fillable = ["source_id", "sales_manager_id", "ad_id"];

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
    
  
    
}