<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

class MoneyEntranceController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(){
        $start_date = Carbon::now()->format('d-m-Y');
        $end_date = $start_date;
        return view('pages.money-entrance.index', compact('start_date', 'end_date'));
    }

    private function getTotalsCash($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        $factors = DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROUND(MAX(SAFACT.Factor), 2) as MaxFactor, CAST(SAFACT.FechaE as date) as FechaE")
            ->whereRaw($interval_query, $queryParams)
            ->groupByRaw("CAST(SAFACT.FechaE as date)");

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) AS CodUsua, CAST(ROUND(SUM(SAFACT.CancelE * SAFACT.Signo), 2) AS decimal(18, 2)) AS bolivares,
            CAST(ROUND((SUM(SAFACT.CancelC * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2)) AS dolares, CAST(SAFACT.FechaE as date) as FechaE")
            ->joinSub($factors, 'FactorHist', function($query){
                $query->on(DB::raw("CAST(SAFACT.FechaE AS date)"), '=', "FactorHist.FechaE");
            })
            ->whereRaw("SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query,
                $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date)")
            ->orderByRaw("CAST(SAFACT.FechaE as date)")
            ->get()
            ->groupBy(['CodUsua', 'FechaE']);
    }

    private function getTotalsEPaymentMethods($start_date, $end_date){

        /* Consulta para obtener los totales de las facturas*/
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        $factors = DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROUND(MAX(SAFACT.Factor), 2) as MaxFactor, CAST(SAFACT.FechaE as date) as FechaE")
            ->whereRaw($interval_query, $queryParams)
            ->groupByRaw("CAST(SAFACT.FechaE as date)");

        return DB
        ::connection('saint_db')
        ->table('SAIPAVTA')
        ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, MAX(SAIPAVTA.CodPago) as CodPago, MAX(CAST(SAFACT.FechaE as date)) as FechaE,
            CASE WHEN SAIPAVTA.CodPago = '07' OR SAIPAVTA.CodPago = '08' 
                THEN CAST(ROUND((SUM(SAIPAVTA.Monto * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))
		    ELSE 
			    CAST(ROUND(SUM(SAIPAVTA.Monto * SAFACT.Signo), 2) AS decimal(18, 2)) END AS total"
        )
        ->joinSub($factors, 'FactorHist', function($query){
            $query->on(DB::raw("CAST(SAIPAVTA.FechaE AS date)"), '=', "FactorHist.FechaE");
        })
        ->join('SAFACT', function($query){
            $query->on("SAFACT.NumeroD", '=', "SAIPAVTA.NumeroD");
        })
        ->whereRaw("SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
            'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query,
            $queryParams)
        ->groupByRaw("SAFACT.CodUsua, SAIPAVTA.CodPago, CAST(SAFACT.FechaE AS date)")
        ->orderByRaw("SAFACT.CodUsua, SAIPAVTA.CodPago")
        ->get()
        ->groupBy(['CodUsua', 'FechaE']);
    }

    private function getPaymentMethods(){
        return DB::table('payment_methods')->get()->groupBy(['CodPago']);
    }

    private function mapEPaymentMethods($data, $payment_methods){

        $totals_saint = [];

        // Iterating every cash register user
        foreach($data->keys() as $cod_usua){
            $totals_saint[$cod_usua] = [];
            // Iterating over every date of cash register user
            foreach ($data[$cod_usua]->keys() as $date){
                $totals_saint[$cod_usua][$date] = [];
                // Mapping every total with its key
                foreach($payment_methods->keys() as $index => $value){
                    $record = $data[$cod_usua][$date]->slice($index, 1)->first();
            
                    if (!is_null($record)){
                        if ($value === $record->CodPago){
                            $totals_saint[$cod_usua][$date][$value] = $record->total;
                        } else {
                            if (!key_exists($value, $totals_saint[$cod_usua][$date])){
                                $totals_saint[$cod_usua][$date][$value] = 0;
                            }
                            $totals_saint[$cod_usua][$date][$record->CodPago] = $record->total;
                        }
                    } else if (!key_exists($value, $totals_saint[$cod_usua][$date])) {
                        $totals_saint[$cod_usua][$date][$value] = 0;
                    }
                }
            }
        }

        return $totals_saint;
    }

    private function getTotalsEpaymentByUser($array, $payment_methods){
        $totals = [];

        foreach($array as $key_user => $dates){
            $totals[$key_user] = [];
            foreach($payment_methods as $codPago => $value){
                $totals[$key_user][$codPago] = 0;
                foreach($dates as $date_record){
                    $totals[$key_user][$codPago] += $date_record[$codPago];
                }
            }
        }

        return $totals;
    }

    private function getTotalsCashByUser($array){
        $currencies = config('constants.CURRENCIES');
        $totals = [];

        foreach($array as $key_user => $dates){
            $totals[$key_user] = [];
            $totals[$key_user]['bolivar'] = 0;
            $totals[$key_user]['dollar'] = 0;
            
            foreach($dates as $key_date => $date_record){
                $totals[$key_user]['bolivar'] += $date_record->first()->bolivares;
                $totals[$key_user]['dollar'] += $date_record->first()->dolares;
            }
        }

        return $totals;
    }

    private function getTotalCashByInterval($array){
        $totals = [];

        foreach($array as $key_user => $entries){

            foreach($entries as $currency => $subtotal){
                if (!key_exists($currency, $totals)){
                    $totals[$currency] = 0;
                } 
                $totals[$currency] += $subtotal;
            } 
        }

        return $totals;
    }

    public function generateReport(Request $request){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        $currency_signs = [
            'dollar' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')),
            'bs' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR'))
        ];

        if ($start_date && $end_date){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $totals_cash = $this->getTotalsCash($new_start_date, $new_finish_date);
            $totals_e_payments = $this->getTotalsEPaymentMethods($new_start_date, $new_finish_date);
            
            $payment_methods = $this->getPaymentMethods();

            $totals_e_payment = $this->mapEPaymentMethods($totals_e_payments, $payment_methods);
            
            $totals_e_payment_by_user = $this->getTotalsEpaymentByUser($totals_e_payment, $payment_methods);

            $totals_cash_by_user = $this->getTotalsCashByUser($totals_cash);

            $totals_cash_by_interval = $this->getTotalCashByInterval($totals_cash_by_user);

            $pdf = App::make('dompdf.wrapper');
            
            $view_name = 'pdf.money-entrance.money_record';
        
            $pdf = $pdf->loadView($view_name, compact(
                    'totals_e_payment',
                    'totals_cash',
                    'totals_e_payment_by_user',
                    'totals_cash_by_user',
                    'currency_signs',
                    'start_date',
                    'end_date',
                    'totals_cash_by_interval'
                ))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->download('entrada_dinero'  . '_' . $start_date . '.pdf');
        }

    }

}
