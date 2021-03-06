<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Subscription extends JsonResource
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
            'pack_id'=>$this->pack_id,
            'pack' => new Pack($this->pack),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'user'=>$this->user,
            'child'=>$this->child,
            'payment'=>$this->payment,
            'token' => $this->token,
            'delivery' => $this->delivery,


        ];
    }
}