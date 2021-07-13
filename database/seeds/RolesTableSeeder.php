<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('roles')->insert([
                [
                    'id' => 1,
                    'name' => 'SCHOOL_ADMIN',
                    'description' => 'School administrator'
                ],
                [
                    'id' => 2,
                    'name' => 'STUDENT',
                    'description' => 'Student'
                ],
                [
                    'id' => 3,
                    'name' => 'TEACHER',
                    'description' => 'Teacher'
                ],
                [
                    'id' => 4,
                    'name' => 'EDITOR',
                    'description' => 'Editor'
                ],
                [
                    'id' => 5,
                    'name' => 'PARENT',
                    'description' => 'Parent'
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }
}
