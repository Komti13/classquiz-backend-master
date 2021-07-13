<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Icon extends JsonResource
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
            'category' => $this->category,
            'is_default' => $this->is_default,
            'atlas_name' => $this->atlas->name,
            'atlas_url' => $this->atlas->is_default ? $this->atlas->url : asset('uploads/icon/atlas/' . $this->atlas->url),
            'atlas_updated_at' => $this->atlas->updated_at ? $this->atlas->updated_at->toDateTimeString() : null,
            'direct_url' => $this->is_default ? $this->direct_url : asset('uploads/icon/direct/' . $this->direct_url),
            'index' => $this->index,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
