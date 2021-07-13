<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentHistory extends Model
{
    use SoftDeletes;

    public function payment(){
        return $this->belongsTo('App\Payment');
    }

    public function paymentStatus(){
        return $this->belongsTo('App\PaymentStatus');
    }
}
