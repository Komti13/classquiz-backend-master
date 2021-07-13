<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizFieldDatum extends Model
{
    protected $fillable =
        [
            'field_type',
            'block_a_x',
            'block_a_y',
            'block_b_x',
            'block_b_y',
            'text_a',
            'text_b',
            'sprite_a',
            'sprite_b',
            'is_first_field',
            'is_last_field',
            'is_active',
            'toggle_value',
            'field_index',
            'group_id',
            'block_a_value',
            'block_b_value',
            'is_child',
            'is_parent',
            'parent_id',
            'question_id'
        ];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

}
