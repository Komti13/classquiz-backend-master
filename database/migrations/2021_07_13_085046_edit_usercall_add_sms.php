<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditUsercallAddSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_calls', function (Blueprint $table) {
            $table->unsignedInteger('sms_id')->nullable();
            $table->foreign('sms_id')->references('id')->on('sms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_call', function (Blueprint $table) {
            //
        });
    }
}