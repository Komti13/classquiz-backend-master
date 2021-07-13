<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalDonutsAndTotalCandiesToAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achivements', function (Blueprint $table) {
            $table->integer('total_donuts')->default(0);
            $table->integer('total_candies')->default(0);
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
            $table->dropColumn('total_donuts');
            $table->dropColumn('total_candies');
        });
    }
}
