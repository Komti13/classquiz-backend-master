<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Payment extends JsonResource
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
            'code' => $this->code,
            'amount' => $this->amount,
            'current_status' => $this->current_status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

        ];
    }
}