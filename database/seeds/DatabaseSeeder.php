<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ChapterTypesTableSeeder::class);
        $this->call(PaymentStatusesTableSeeder::class);
        $this->call(PaymentMethodsTableSeeder::class);
        $this->call(TransactionTypesTableSeeder::class);
        $this->call(PackTypesTableSeeder::class);
    }
}
