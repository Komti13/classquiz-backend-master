<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackQuestionGroupPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pack_question_group', function (Blueprint $table) {
            $table->integer('pack_id')->unsigned()->index();
            $table->foreign('pack_id')->references('id')->on('packs')->onDelete('cascade');
            $table->integer('question_group_id')->unsigned()->index();
            $table->foreign('question_group_id')->references('id')->on('question_groups')->onDelete('cascade');
            $table->primary(['pack_id', 'question_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pack_question_group');
    }
}
