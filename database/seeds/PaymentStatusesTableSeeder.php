<?php

use Illuminate\Database\Seeder;

class PaymentStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('payment_statuses')->insert([
                [
                    'id' => 1,
                    'name' => 'Pending'
                ],
                [
                    'id' => 2,
                    'name' => 'Accepted'
                ],
                [
                    'id' => 3,
                    'name' => 'Refused'
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }
}
