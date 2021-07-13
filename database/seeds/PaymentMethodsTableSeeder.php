<?php

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::table('payment_methods')->insert([
                [
                    'id' => 1,
                    'name' => 'Bank transfer'
                ],
                [
                    'id' => 2,
                    'name' => 'Credit Card'
                ],
                [
                    'id' => 3,
                    'name' => 'Delivery'
                ]
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
        }
    }
}
