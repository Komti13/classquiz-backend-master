<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('atlas_id')->nullable();
            $table->foreign('atlas_id')->references('id')->on('atlases')->onDelete('cascade');
            $table->string('name');
            $table->integer('category');
            $table->boolean('is_default')->default(1);
            $table->integer('index');
            $table->string('direct_url')->nullable();
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
        Schema::drop('icons');
    }
}
