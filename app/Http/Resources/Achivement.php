<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Achivement extends JsonResource
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
            'donuts' => $this->donuts,
            'candy' => $this->candy,
            'nb_completed_chapter' => $this->nb_completed_chapter,
            'total_time' => $this->total_time,
            'total_donuts' => $this->total_donuts,
            'total_candies' => $this->total_candies,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
