<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;


class PaymentMethodSeeder extends Seeder
{
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr =  DB::connection('saint_db')->table('SATARJ')
            ->selectRaw("CodTarj as CodPago, Descrip")
            ->whereIn("CodTarj", ['06', '100', '101'], 'and', true)
            ->get()
            ->all();
        
        $arr = array_map(function($item){
            return ['CodPago' => $item->CodPago,'Descrip' => $item->Descrip];
        }, $arr);
        
        DB::connection('caja_mayorista')
            ->table('payment_methods')
            ->upsert($arr, ['CodPago'], ['CodPago', 'Descrip']);
    }
}
