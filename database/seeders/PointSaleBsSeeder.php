<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\PointSaleBsRecord;

class PointSaleBsSeeder extends Seeder
{
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $old_point_sale_bs_records =  DB::connection('caja_mayorista')->table('point_sale_bs_records')
            ->select('*')
            ->get()
            ->groupBy(['cash_register_data_id', 'bank_name']);

        $rows = [];
        foreach($old_point_sale_bs_records as $key_cash_register_data_id => $banks){
            foreach($banks as $key_bank_name => $bank){
                $row = [];
                foreach($bank as $record) {
                    if ($record->type === 'DEBIT'){
                        $row['cancel_debit'] = $record->amount;
                    } else {
                        $row['cancel_credit'] = $record->amount;
                    }
                }
                $row['bank_name'] = $key_bank_name;
                $row['cash_register_data_id'] = $key_cash_register_data_id;
                $rows[] = $row;
            }
        }
  
        PointSaleBsRecord::insert($rows);
    }
}
