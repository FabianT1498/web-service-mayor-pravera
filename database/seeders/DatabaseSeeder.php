<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use  Database\Seeders\BankSeeder;

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
        ]);
    }
}
