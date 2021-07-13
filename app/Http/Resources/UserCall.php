<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCall extends JsonResource
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
            'actual_status' => $this->actual_status,
            'notes' => $this->notes,
            'created_at' => $this->created_at,//->toDateTimeString(),
            'updated_at' => $this->updated_at,//->toDateTimeString(),
            'conversation_date' => $this->conversation_date,//->toDateTimeString(),
            'Sms' => $this->sms,
            'sales_info' => $this->salesInfo,
            'user' => $this->user
        ];
    }
}