<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Homework extends JsonResource
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
            'user_id' => $this->user_id,
            'question_group_id' => $this->question_group_id,
            'score' => $this->score,
            'time' => $this->time,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}