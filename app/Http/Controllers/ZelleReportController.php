<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;


use App\Repositories\CashRegisterRepository;
use App\Exports\ZelleDataExport;

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

            $factors = $cash_register_repo
                ->getFactorByDate($new_start_date, $new_finish_date)
                ->groupBy(['FechaE']);

            // Zelle from Local Database
            $zelle_records = $cash_register_repo
                ->getZelleRecords($new_start_date, $new_finish_date)
                ->groupBy(['cash_register_user', 'date']);
 
            $total_zelle_amount_by_user = $this->getTotalZelleAmountByUser($zelle_records, $factors);

            $total_zelle_amount =  $this->getTotalZelleAmount($total_zelle_amount_by_user);

            // Zelle total from SAINT
            $zelle_records_from_saint = $cash_register_repo
                ->getZelleRecordsFromSaint($new_start_date, $new_finish_date)
                ->groupBy(['CodEsta', 'FechaE']);

            $total_zelle_amount_by_user_from_saint = $cash_register_repo
                ->getZelleTotalByUserFromSaint($new_start_date, $new_finish_date)
                ->groupBy(['CodEsta']);

            $total_zelle_amount_from_saint = $cash_register_repo
                ->getZelleTotalFromSaint($new_start_date, $new_finish_date);
                
            $file_name = 'Detalles_Zelle_' . ($new_start_date === $new_finish_date 
                ? $start_date 
                : 'desde_' . $start_date . '_hasta_' . $end_date
                )
                . '.xlsx';

            return Excel::download(new ZelleDataExport(compact(
                'zelle_records',
                'total_zelle_amount_by_user',
                'factors',
                'total_zelle_amount',
                'zelle_records_from_saint',
                'total_zelle_amount_by_user_from_saint',
                'total_zelle_amount_from_saint',
                'start_date',
                'end_date')
            ), $file_name);
        }
    }

    public function generatePDF(Request $request, CashRegisterRepository $cash_register_repo){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $factors = $cash_register_repo
                ->getFactorByDate($new_start_date, $new_finish_date)
                ->groupBy(['FechaE']);

            // Zelle from Local Database
            $zelle_records = $cash_register_repo
                ->getZelleRecords($new_start_date, $new_finish_date)
                ->groupBy(['cash_register_user', 'date']);
 
            $total_zelle_amount_by_user = $this->getTotalZelleAmountByUser($zelle_records, $factors);

            $total_zelle_amount =  $this->getTotalZelleAmount($total_zelle_amount_by_user);

            // Point of sale dollar from Local Database
            $point_sale_dollar_records = $cash_register_repo
                ->getPointSaleDollarRecords($new_start_date, $new_finish_date)
                ->groupBy(['cash_register_user', 'date']);

            $total_point_sale_dollar_amount_by_user = $this->getTotalZelleAmountByUser($point_sale_dollar_records, $factors);

            $total_point_sale_dollar_amount =  $this->getTotalZelleAmount($total_point_sale_dollar_amount_by_user);

            // Zelle total from SAINT
            $zelle_records_from_saint = $cash_register_repo
                ->getZelleRecordsFromSaint($new_start_date, $new_finish_date)
                ->groupBy(['CodEsta', 'FechaE']);

            $total_zelle_amount_by_user_from_saint = $cash_register_repo
                ->getZelleTotalByUserFromSaint($new_start_date, $new_finish_date)
                ->groupBy(['CodEsta']);

            $total_zelle_amount_from_saint = $cash_register_repo
                ->getZelleTotalFromSaint($new_start_date, $new_finish_date);

            // Point of sale dollar from SAINT
            $point_sale_dollar_records_saint = $cash_register_repo
                ->getPointSaleDollarRecordsFromSaint($new_start_date, $new_finish_date)
                ->groupBy(['CodEsta', 'FechaE']);

            $total_point_sale_dollar_amount_by_user_saint = $cash_register_repo
                ->getPointSaleDollarTotalByUserFromSaint($new_start_date, $new_finish_date)
                ->groupBy(['CodEsta']);

            $total_point_sale_dollar_from_saint = $cash_register_repo
                ->getPointSaleDollarTotalFromSaint($new_start_date, $new_finish_date);
     
            $file_name = 'Detalles_Zelle_' . ($new_start_date === $new_finish_date 
                ? $start_date 
                : 'desde_' . $start_date . '_hasta_' . $end_date
                )
                . '.pdf';

            $pdf = App::make('dompdf.wrapper');

            $view_name = 'pdf.zelle.detalles-zelle-report';

            $data = compact(
                'start_date',
                'end_date',
                'zelle_records',
                'total_zelle_amount_by_user',
                'total_zelle_amount',
                'zelle_records_from_saint',
                'total_zelle_amount_by_user_from_saint',
                'total_zelle_amount_from_saint',
                'point_sale_dollar_records',
                'total_point_sale_dollar_amount_by_user',
                'total_point_sale_dollar_amount',
                'point_sale_dollar_records_saint',
                'total_point_sale_dollar_amount_by_user_saint',
                'total_point_sale_dollar_from_saint',
                'factors'
            );

            $pdf = $pdf->loadView($view_name, $data)
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->stream($file_name);
        }
    }
}