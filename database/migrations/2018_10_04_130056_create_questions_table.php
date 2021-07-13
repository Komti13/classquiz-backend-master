<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_group_id')->nullable();
            $table->foreign('question_group_id')->references('id')->on('question_groups')->onDelete('cascade');
            $table->unsignedInteger('chapter_id');
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            $table->string('template_id')->nullable();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');
            $table->string('title')->nullable();
            $table->string('main_question')->nullable();
            $table->string('sub_question')->nullable();
            $table->string('score');
            $table->boolean('is_confirmed');
            $table->boolean('has_warning');
            $table->boolean('generate_on_layout');
            $table->boolean('is_new_question');
            $table->longText('situation')->nullable();
            $table->string('hints')->nullable();
            $table->string('bg_color')->nullable();
            $table->integer('index_in_group')->nullable();
            $table->integer('time')->nullable();
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
        Schema::drop('questions');
    }
}
