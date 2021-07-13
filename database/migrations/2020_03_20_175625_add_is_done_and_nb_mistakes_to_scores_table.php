<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDoneAndNbMistakesToScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->boolean('is_done')->default(false);
            $table->integer('nb_mistakes')->nullable();
        });
        DB::table('scores')->where('score', '=',0)->update(['is_done' => false]);
        DB::table('scores')->where('score', '>',0)->update(['is_done' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropColumn('is_done');
            $table->dropColumn('nb_mistakes');
        });
    }
}
