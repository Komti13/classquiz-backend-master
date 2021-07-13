<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Delivery extends JsonResource
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
            'delivery_fees' => $this->delivery_fees,
            'delivery_date' => $this->delivery_date,
            'delivery_status' => $this->delivery_status,
            'double_delivery' => $this->double_delivery,
            'payment_id' => $this->payment_id,
        ];
    }
}