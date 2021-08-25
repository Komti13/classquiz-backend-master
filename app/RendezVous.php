<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    protected $fillable = ["user_id", "sales_man_id"];
    public function salesManager()
    {
        return $this->belongsTo('App\Admin');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
