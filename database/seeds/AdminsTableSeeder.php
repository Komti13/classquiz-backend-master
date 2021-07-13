<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('admins')->insert([
                'id' => 1,
                'name' => 'Achref',
                'email' => 'achref.daouahi@gmail.com',
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }
}
