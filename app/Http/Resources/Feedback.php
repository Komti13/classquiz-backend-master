<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Feedback extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'user' => $this->user,
            'user_call' => $this->userCall,
        ];
    }
}