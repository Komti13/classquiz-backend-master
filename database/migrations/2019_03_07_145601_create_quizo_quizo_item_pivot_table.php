<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizoQuizoItemPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizo_quizo_item', function (Blueprint $table) {
            $table->integer('quizo_id')->unsigned()->index();
            $table->foreign('quizo_id')->references('id')->on('quizos')->onDelete('cascade');
            $table->integer('quizo_item_id')->unsigned()->index();
            $table->foreign('quizo_item_id')->references('id')->on('quizo_items')->onDelete('cascade');
            $table->primary(['quizo_id', 'quizo_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quizo_quizo_item');
    }
}
