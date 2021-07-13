<?php

use Illuminate\Database\Seeder;

class ChapterTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('chapter_types')->insert([
                [
                    'id' => 1,
                    'name' => 'أسئلة ذكاء'
                ],
                [
                    'id' => 2,
                    'name' => 'تمارين مدرسية'
                ],
                [
                    'id' => 3,
                    'name' => 'إمتحانات'
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }
}
