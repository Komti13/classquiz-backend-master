<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Score extends JsonResource
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
            'score' => $this->score,
            'time' => $this->time,
            'user_id' => $this->user_id,
            'question_group_id' => $this->question_group_id,
            'is_done' => $this->is_done,
            'nb_mistakes' => $this->nb_mistakes,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
