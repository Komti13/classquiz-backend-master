<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTotalDonutsAndTotalCandiesNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achivements', function (Blueprint $table) {
            $table->integer('total_donuts')->default(0)->nullable()->change();
            $table->integer('total_candies')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('achivements', function (Blueprint $table) {
            $table->integer('total_donuts')->default(0)->change();
            $table->integer('total_candies')->default(0)->change();
        });
    }
}
