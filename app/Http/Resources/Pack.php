<?php

namespace App\Http\Resources;
use App\PackType;
use Illuminate\Http\Resources\Json\JsonResource;

class Pack extends JsonResource
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
            'description' => $this->description,
            'level_id' => $this->levle_id,
            'pack_type_id' => $this->pack_type_id,
            'validity_end' => $this->validity_end,
            'validity_start' => $this->validity_start,
            'price' => (int) $this->price,
            'promotion' => $this->when($this->activePackPromotion(), true, false),
            'new_price' => (int) optional($this->activePackPromotion())->value,
            'early_bird' => $this->when($this->activePackPromotion(), optional($this->activePackPromotion())->early_bird, false),
            'is_free' => $this->is_free,
            'is_valid' => $this->is_valid,
            'subjects' => $this->subjects(),
            'order' => $this->order,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'pack_type'=>$this->packType,
            'level'=>$this->level,


        ];
    }
}
