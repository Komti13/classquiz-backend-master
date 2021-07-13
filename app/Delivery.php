<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ["delivery_fees", "delivery_date", "delivery_status","double_delivery"];

    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }

}