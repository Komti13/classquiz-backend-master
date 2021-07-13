<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('type');
        });

        Schema::create('source', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('type');
        });
        Schema::create('sales_info', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('sales_manager_id');
            $table->unsignedInteger('source_id');
            $table->unsignedInteger('ad_id');
            $table->foreign('sales_manager_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('source_id')->references('id')->on('source')->onDelete('cascade');
            $table->foreign('ad_id')->references('id')->on('ad')->onDelete('cascade');

        });
        Schema::create('status', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('conversation_date');
            $table->string('notes');
            $table->boolean('SMS_sent');
            $table->string('actual_status');
            $table->unsignedInteger('sales_info_id');
            $table->foreign('sales_info_id')->references('id')->on('sales_info')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status');
        Schema::dropIfExists('source');
        Schema::dropIfExists('ad');
        Schema::dropIfExists('sales_info');


    }
}