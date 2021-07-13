<?php

use Illuminate\Database\Seeder;

class PackTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('pack_types')->insert([
                [
                    'id' => 1,
                    'name' => 'Annual'
                ],
                [
                    'id' => 2,
                    'name' => 'Quarter'
                ],
                [
                    'id' => 3,
                    'name' => 'Subject'
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }
}
