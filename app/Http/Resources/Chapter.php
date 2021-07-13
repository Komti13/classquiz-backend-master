<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Chapter extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'description' => $this->description,
            'time' => $this->time,
            'chapter_type_id' => optional($this->chapterType)->id,
            'icon' => asset('uploads/chapter/' . $this->icon),
            'subject_id' => $this->subject_id,
            'levels' => $this->levelId ? ChapterLevels::collection($this->levels->where('id', $this->levelId)) : ChapterLevels::collection($this->levels),
            'unlocked' => isset($this->guestUnlocked) ? $this->guestUnlocked : $this->unlocked(),
            'is_free' => isset($this->guestIsFree) ? $this->guestIsFree : $this->isFree(),
            'related' => $this->related,
            'purchased' => $this->purchased(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
