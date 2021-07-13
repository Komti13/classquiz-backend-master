<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterLevels extends JsonResource
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
            'level_id' => $this->pivot->level_id,
            'chapter_id' => $this->pivot->chapter_id,
            'order' => $this->pivot->order,
        ];
    }
}
