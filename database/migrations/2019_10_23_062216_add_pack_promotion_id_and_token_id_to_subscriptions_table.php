<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackPromotionIdAndTokenIdToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('pack_promotion_id')->unsigned()->index()->nullable();
            $table->foreign('pack_promotion_id')->references('id')->on('pack_promotions')->onDelete('cascade');
            $table->integer('token_id')->unsigned()->index()->nullable();
            $table->foreign('token_id')->references('id')->on('tokens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['pack_promotion_id']);
            $table->dropColumn('pack_promotion_id');
            $table->dropForeign(['token_id']);
            $table->dropColumn('token_id');
        });
    }
}