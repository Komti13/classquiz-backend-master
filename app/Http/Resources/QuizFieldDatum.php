<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizFieldDatum extends JsonResource
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
            'field_type' => $this->field_type,
            'block_a_x' => $this->block_a_x,
            'block_a_y' => $this->block_a_y,
            'block_b_x' => $this->block_b_x,
            'block_b_y' => $this->block_b_y,
            'text_a' => $this->text_a,
            'text_b' => $this->text_b,
            'sprite_a' => $this->sprite_a,
            'sprite_b' => $this->sprite_b,
            'is_first_field' => $this->is_first_field,
            'is_last_field' => $this->is_last_field,
            'is_active' => $this->is_active,
            'toggle_value' => $this->toggle_value,
            'field_index' => $this->field_index,
            'group_id' => $this->group_id,
            'block_a_value' => $this->block_a_value,
            'block_b_value' => $this->block_b_value,
            'is_child' => $this->is_child,
            'is_parent' => $this->is_parent,
            'parent_id' => $this->parent_id,
            'question_id' => $this->question_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
