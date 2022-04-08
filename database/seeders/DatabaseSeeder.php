<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use  Database\Seeders\BankSeeder;
use  Database\Seeders\CashRegisterUserSeeder;
use  Database\Seeders\PaymentMethodSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Worker::factory(10)->create();

        $this->call([
            BankSeeder::class,
            CashRegisterUserSeeder::class,
            PaymentMethodSeeder::class
        ]);
    }
}
