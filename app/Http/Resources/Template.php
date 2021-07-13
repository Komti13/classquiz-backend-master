<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Template extends JsonResource
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
            'selected_template' => $this->selected_template,
            'selected_category' => $this->selected_category,
            'selected_icon' => $this->selected_icon,
            'with_last_field' => $this->with_last_field,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
