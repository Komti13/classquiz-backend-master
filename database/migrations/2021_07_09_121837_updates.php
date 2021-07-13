<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->double('delivery_fees')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_status')->nullable();
            $table->boolean('double_delivery')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments',function (Blueprint $table) {
        $table->dropColumn('delivery_fees');
        $table->dropColumn('delivery_date');
        $table->dropColumn('delivery_status');
        $table->dropColumn('double_delivery');
        });
    }
}