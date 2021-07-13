<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneColumnToUserPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_password_resets', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('phone')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_password_resets', function (Blueprint $table) {
            $table->string('email')->change();
            $table->dropColumn('phone');
        });
    }
}
