<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pack_promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pack_id')->unsigned()->index();
            $table->foreign('pack_id')->references('id')->on('packs')->onDelete('cascade');
            $table->date('start');
            $table->date('end');
            $table->integer('value');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pack_promotions');
    }
}
