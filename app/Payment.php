<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $appends = ['current_status'];

    public function getCurrentStatusAttribute(){
        return optional($this->paymentHistories()->latest()->first())->paymentStatus;
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function paymentMethod(){
        return $this->belongsTo('App\PaymentMethod');
    }

    public function paymentHistories(){
        return $this->hasMany('App\PaymentHistory');
    }

    public function subscription(){
        return $this->belongsTo('App\Subscription');
    }

}
