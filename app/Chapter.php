<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use SoftDeletes;

    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    public function levels()
    {
        return $this->belongsToMany('App\Level')->withPivot('order');
    }

    public function questionGroups()
    {
        return $this->hasMany('App\QuestionGroup');
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function chapterType()
    {
        return $this->belongsTo('App\ChapterType');
    }

    public function userProgress()
    {
        $user = auth()->guard('api')->user();
        $userChapterScore = Score::where('user_id', $user->id)
            ->whereHas('questionGroup', function ($q) {
                $q->where('chapter_id', $this->id);
            })->sum('score');
        $chapterScore = $this->questions->sum('score');
        $chapterProgress = 0;
        if ($this->questionGroups->count() && $chapterScore) {
            $chapterProgress = $userChapterScore / $chapterScore;
        }

        return $chapterProgress;
    }

    public function unlocked($levelId = null)
    {
        $user = auth()->guard('api')->user();
        if ($user) {
            $chapter = $user->level->chapters->where('id', $this->id)->first();
        } else {
            $chapter = $this->levels->where('id', $levelId)->first();
        }
        if ($this->related && isset($chapter->pivot->order) && $chapter->pivot->order == 0) {
            return true;
        }
        if (!$user && $this->related) {
            return false;
        }
        if (!$this->related) {
            return true;
        }

        $previousChapter = $user->level->chapters
            ->where('pivot.order', '!=', null)
            ->where('pivot.order', '<', $chapter->pivot->order)
            ->first();

        return $previousChapter->userProgress() >= 0.7;
    }

    public function isFree($levelId = null)
    {
        $user = auth()->guard('api')->user();
        if ($user) {
            $chapter = $user->level->chapters->where('id', $this->id)->first();
        } else {
            $chapter = $this->levels->where('id', $levelId)->first();
        }
        if ((isset($this->chapterType) && $this->chapterType->id == 1) || (isset($chapter->pivot->order) && $chapter->pivot->order == 0)) {
            return true;
        }
        return false;
    }

    public function purchased()
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return false;
        }
        foreach ($user->validSubscriptions as $subscription) {
            if ($subscription->pack->chapters->contains('id', $this->id)) {
                return true;
            }
        };
        return false;
    }
}
