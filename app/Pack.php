<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pack extends Model
{
    use SoftDeletes;

    protected $appends = ['is_free', 'is_valid'];

    public function getIsFreeAttribute()
    {
        return $this->price == 0;
    }

    public function getIsValidAttribute()
    {
        return $this->validity_start <= Carbon::now() && $this->validity_end >= Carbon::now();
    }

    public function subject()
    {
        if ($this->packType()->id == 3) {
            return $this->chapters()->first()->subject;
        }
        return null;
    }

    public function packType()
    {
        return $this->belongsTo('App\PackType');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function chapters()
    {
        return $this->belongsToMany('App\Chapter');
    }

    public function subjects()
    {
        $subjectIds = $this->chapters->pluck('subject_id');
        $chapterIds = $this->chapters->pluck('id');
        return Subject::whereIn('id', $subjectIds)
            ->with(['chapters' => function ($q) use ($chapterIds) {
                $q->whereIn('id', $chapterIds)
                ->select('id','subject_id');
            }])
            ->get();
    }

    public function packPromotions()
    {
        return $this->hasMany('App\PackPromotion');
    }

    public function activePackPromotion()
    {
        return $this->packPromotions()
            ->whereDate('start', '<=', Carbon::now())
            ->whereDate('end', '>=', Carbon::now())
            ->first();
    }
}
