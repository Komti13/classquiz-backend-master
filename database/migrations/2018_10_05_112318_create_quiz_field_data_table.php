<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizFieldDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_field_data', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id')->nullable();
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->string('field_type');
            $table->float('block_a_x')->nullable();
            $table->float('block_a_y')->nullable();
            $table->float('block_b_x')->nullable();
            $table->float('block_b_y')->nullable();
            $table->longText('text_a')->nullable();
            $table->longText('text_b')->nullable();
            $table->string('sprite_a')->nullable();
            $table->string('sprite_b')->nullable();
            $table->boolean('is_first_field');
            $table->boolean('is_last_field');
            $table->boolean('is_active');
            $table->boolean('toggle_value');
            $table->integer('field_index')->nullable();
            $table->string('group_id')->nullable();
            $table->string('block_a_value')->nullable();
            $table->string('block_b_value')->nullable();
            $table->boolean('is_child')->nullable()->default(0);
            $table->boolean('is_parent')->nullable()->default(0);
            $table->string('parent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quiz_field_data');
    }
}
