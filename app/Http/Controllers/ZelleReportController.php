<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;

use App\Repositories\CashRegisterRepository;
use App\Exports\ZelleRecordsExport;

class ZelleReportController extends Controller
{
    private $flasher = null;

    public function index(){
        $start_date = Carbon::now()->format('d-m-Y');
        $end_date = $start_date;
        return view('pages.zelle-report.index', compact('start_date', 'end_date'));
    }

    private function getTotalZelleAmountByUser($zelle_records, $factors){
        $total_by_user = [];

        foreach($zelle_records as $key_codusua => $dates){
            $total_by_user[$key_codusua] = [];
            $total_by_user[$key_codusua]['dollar'] = 0; 
            $total_by_user[$key_codusua]['bs'] = 0;
            foreach ($dates as $key_date => $records){
                $total_dollar = $records->reduce(function($acc, $item){
                    return $acc + $item->amount;
                }, 0);

                $total_by_user[$key_codusua]['dollar'] += $total_dollar;
                $total_by_user[$key_codusua]['bs'] += ($total_dollar * $factors[$key_date]->first()->MaxFactor);
            }
        }

        return $total_by_user;
    }

    private function getTotalZelleAmount($total_by_user){
        $total = ['dollar' => 0, 'bs' => 0];

        $total = array_reduce($total_by_user, function($acc, $el){
            $acc['dollar'] += $el['dollar'];
            $acc['bs'] += $el['bs'];

            return $acc;
        }, $total);

        return $total;
    }

    public function generateExcel(Request $request, CashRegisterRepository $cash_register_repo){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $zelle_records = $cash_register_repo
                ->getZelleRecords($new_start_date, $new_finish_date)
                ->groupBy(['cash_register_user', 'date']);

                
            $factors = $cash_register_repo
                ->getFactorByDate($new_start_date, $new_finish_date)
                ->groupBy(['FechaE']);
                
            $total_zelle_amount_by_user = $this->getTotalZelleAmountByUser($zelle_records, $factors);

            $total_zelle_amount =  $this->getTotalZelleAmount($total_zelle_amount_by_user);

            $file_name = 'Detalles_Zelle_' . ($new_start_date === $new_finish_date 
                ? $start_date 
                : 'desde_' . $start_date . '_hasta_' . $end_date
                )
                . '.xlsx';

            return Excel::download(new ZelleRecordsExport(compact(
                'zelle_records',
                'total_zelle_amount_by_user',
                'factors',
                'total_zelle_amount',
                'start_date',
                'end_date')
            ), $file_name);
        }
    }
}