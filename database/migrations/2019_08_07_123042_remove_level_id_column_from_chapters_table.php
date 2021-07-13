<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveLevelIdColumnFromChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //move old data if exist to pivot
        $chapters = App\Chapter::all();
        foreach ($chapters as $chapter) {
            if ($chapter->level_id) {
                $chapter->levels()->sync($chapter->level_id);
            }
        }
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropColumn('level_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->integer('level_id')->unsigned()->index()->nullable();
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('set null');
        });
    }
}
