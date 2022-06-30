<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class BankSeeder extends Seeder
{
    private $banks = ['Venezuela', 'Del Sur', 'Banesco',
        'Mercantil', 'Banplus', 'Banco Nacional de Credito', 'Exterior', 'Provincial'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach($this->banks as $bank){
            DB::connection('caja_mayorista')->table('banks')->insert([
                'name' => Str::upper($bank)
            ]);
        }
    }
}
