<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;


class CashRegisterUserSeeder extends Seeder
{
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr =  DB::connection('saint_db')->table('SSUSRS')
            ->select('CodUsua as name')
            ->where("CodUsua", "LIKE", "CAJA%")
            ->where("CodUsua", "=", "DELIVERY", 'or')
            ->get()
            ->all();
        
        $arr = array_map(function($item){
            return ['name' => $item->name];
        }, $arr);
        
        DB::connection('caja_mayorista')
            ->table('cash_register_users')
            ->upsert($arr, ['name'], ['name']);
    }
}
