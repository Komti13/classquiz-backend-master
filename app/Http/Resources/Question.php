<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Question extends JsonResource
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
            'title' => $this->title,
            'main_question' => $this->main_question,
            'sub_question' => $this->sub_question,
            'score' => $this->score,
            'is_confirmed' => $this->is_confirmed,
            'has_warning' => $this->has_warning,
            'generate_on_layout' => $this->generate_on_layout,
            'is_new_question' => $this->is_new_question,
            'situation' => $this->situation,
            'hints' => $this->hints,
            'bg_color' => $this->bg_color,
            'time' => $this->time,
            'chapter_id' => $this->chapter_id,
            'template_id' => $this->template_id,
            'index_in_group' => $this->index_in_group,
            'question_group_id' => $this->question_group_id,
            //'quiz_field_data' => QuizFieldDatum::collection($this->QuizFieldData),
            'extension' => $this->extension,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
