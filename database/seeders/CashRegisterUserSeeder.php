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
        
        $stations = ['CAJA1' => 'CAJA-1', 'CAJA2' => 'CAJA2', 'CAJA3' => 'CAJA-3', 'CAJA4' => 'CAJA4', 'CAJA5' => 'CAJA5', 'CAJA6' => 'CAJA6', 'CAJA7' => 'CAJA7', 'DELIVERY' => 'DELIVERYPB'];
        
        $arr = array_map(function($item) use ($stations){
            return ['name' => $item->name, 'station' => $stations[$item->name]];
        }, $arr);

        
        DB::connection('web_services_db')
            ->table('cash_register_users')
            ->upsert($arr, ['name'], ['name', 'station']);
    }
}
