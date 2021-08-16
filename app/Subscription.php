<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function foo\func;

class Subscription extends Model
{
    use SoftDeletes;

    protected $appends = ['final_price'];

    public function getFinalPriceAttribute()
    {
        $price = $this->pack->price;

        if ($this->pack->activePackPromotion()) {
            $price = $this->pack->activePackPromotion()->value;
        }
        if ($this->token) {
            $price = $price - $this->token->value;
        }

        return $price;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function child()
    {
        return $this->belongsTo('App\User');
    }

    public function pack()
    {
        return $this->belongsTo('App\Pack');
    }


    public function packPromotion()
    {
        return $this->belongsTo('App\PackPromotion');
    }

    public function token()
    {
        return $this->belongsTo('App\Token');
    }

    public function payment()
    {
        return $this->hasOne('App\Payment');
    }
    public function delivery()
    {
        return $this->hasOne('App\Delivery');
    }

}
