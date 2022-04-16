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

    private function getTotalsFromSafact($start_date, $end_date){
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
            ->selectRaw("MAX(SAFACT.CodUsua) AS CodUsua, CAST(ROUND(SUM(SAFACT.CancelE * SAFACT.Signo), 2) AS decimal(18, 2))  AS bolivares,
            CAST(ROUND((SUM(SAFACT.CancelE * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))  AS bolivaresADolares,
            CAST(ROUND((SUM(SAFACT.CancelC * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))  AS dolares,
            CAST(ROUND(SUM(SAFACT.Credito * SAFACT.Signo), 2) AS decimal(18, 2)) AS credito,
            CAST(ROUND((SUM(SAFACT.Credito * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))  AS creditoADolares,
            CAST(ROUND(MAX(FactorHist.MaxFactor), 2) AS decimal(18, 2)) as Factor,
            CAST(SAFACT.FechaE as date) as FechaE")
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
            CAST(ROUND(SUM(SAIPAVTA.Monto * SAFACT.Signo), 2) AS decimal(18, 2)) as totalBs,
            CAST(ROUND(MAX(FactorHist.MaxFactor), 2) AS decimal(18, 2)) as Factor,
            CAST(ROUND((SUM(SAIPAVTA.Monto * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2)) as totalDollar"
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
        ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE AS date), SAIPAVTA.CodPago")
        ->orderByRaw("SAFACT.CodUsua asc, SAIPAVTA.CodPago asc")
        ->get()
        ->groupBy(['CodUsua', 'FechaE']);
    }

    private function getPaymentMethods(){
        return DB::table('payment_methods')->orderByRaw("CodPago asc")->get()->groupBy(['CodPago']);
    }

    // Método para completar los métodos de pagos que no han tenido ingresos
    // por cada caja en cada fecha.
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
                            $totals_saint[$cod_usua][$date][$value]['bs'] = $record->totalBs;
                            $totals_saint[$cod_usua][$date][$value]['dollar'] = $record->totalDollar;
                        } else {
                            if (!key_exists($value, $totals_saint[$cod_usua][$date])){
                                $totals_saint[$cod_usua][$date][$value]['bs'] = 0.00;
                                $totals_saint[$cod_usua][$date][$value]['dollar'] = 0.00;
                            }
                            $totals_saint[$cod_usua][$date][$record->CodPago]['bs'] = $record->totalBs;
                            $totals_saint[$cod_usua][$date][$record->CodPago]['dollar'] = $record->totalDollar;
                        }
                    } else if (!key_exists($value, $totals_saint[$cod_usua][$date])) {
                        $totals_saint[$cod_usua][$date][$value]['bs'] = 0.00;
                        $totals_saint[$cod_usua][$date][$value]['dollar'] = 0.00;
                    }
                }
            }
        }

        return $totals_saint;
    }

    private function getTotalEpaymentByUser($array, $payment_methods){
        $totals = [];

        foreach($array as $key_user => $dates){
            $totals[$key_user] = [];
            foreach($payment_methods as $codPago => $value){
                $totals[$key_user][$codPago] = [];
                $totals[$key_user][$codPago]['bs'] = 0;
                $totals[$key_user][$codPago]['dollar'] = 0;
                foreach($dates as $date_record){
                    $totals[$key_user][$codPago]['bs'] += $date_record[$codPago]['bs'];
                    $totals[$key_user][$codPago]['dollar'] += $date_record[$codPago]['dollar'];
                }
            }
        }

        return $totals;
    }

    private function getTotalFromSafactByUser($array){
        $totals = [];

        foreach($array as $key_user => $dates){
            $totals[$key_user] = [];
            $totals[$key_user]['bolivar'] = 0;
            $totals[$key_user]['dollar'] = 0;
            $totals[$key_user]['bolivarToDollar'] = 0;
            $totals[$key_user]['credito'] = 0;
            $totals[$key_user]['creditoToDollar'] = 0;
            
            foreach($dates as $key_date => $date_record){
                $totals[$key_user]['bolivar'] += $date_record->first()->bolivares;
                $totals[$key_user]['dollar'] += $date_record->first()->dolares;
                $totals[$key_user]['bolivarToDollar'] += $date_record->first()->bolivaresADolares;
                $totals[$key_user]['credito'] += $date_record->first()->credito;
                $totals[$key_user]['creditoToDollar'] += $date_record->first()->creditoADolares;

            }
        }

        return $totals;
    }
    
    // Method to get an absolute total of cash entries
    private function getTotalFromSafactByInterval($array){
        $totals = [];

        foreach($array as $key_user => $entries){

            foreach($entries as $key_total => $subtotal){
                if (!key_exists($key_total, $totals)){
                    $totals[$key_total] = 0;
                } 
                $totals[$key_total] += $subtotal;
            } 
        }

        return $totals;
    }

    // Method to get an absolute total of e-payments entries
    private function getTotalEPaymentByInterval($array){
        $totals = [];

        foreach($array as $key_user => $entries){

            foreach($entries as $codPago => $currencies){
                if (!key_exists($codPago, $totals)){
                    $totals[$codPago] = [];
                    $totals[$codPago]['bs'] = 0;
                    $totals[$codPago]['dollar'] = 0;
                } 
                $totals[$codPago]['bs'] += $currencies['bs'];
                $totals[$codPago]['dollar'] += $currencies['dollar'];
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

            $totals_safact = $this->getTotalsFromSafact($new_start_date, $new_finish_date);
            $totals_e_payment = $this->getTotalsEPaymentMethods($new_start_date, $new_finish_date);
            
            return print_r($totals_safact);

            $payment_methods = $this->getPaymentMethods();

            $totals_e_payment  = $this->mapEPaymentMethods($totals_e_payment, $payment_methods);
            
            $totals_e_payment_by_user = $this->getTotalEpaymentByUser($totals_e_payment, $payment_methods);

            $totals_safact_by_user = $this->getTotalFromSafactByUser($totals_safact);

            $totals_safact_by_interval = $this->getTotalFromSafactByInterval($totals_safact_by_user);

            $totals_e_payment_by_interval = $this->getTotalEPaymentByInterval($totals_e_payment_by_user);

            $total_dollars = $totals_e_payment_by_interval['07']['dollar'] 
                    + $totals_e_payment_by_interval['08']['dollar'] 
                    + $totals_safact_by_interval['dollar'];
            
            $total_bs_to_dollars = $totals_e_payment_by_interval['01']['dollar'] 
                + $totals_e_payment_by_interval['02']['dollar'] 
                + $totals_e_payment_by_interval['03']['dollar']
                + $totals_e_payment_by_interval['04']['dollar']
                + $totals_e_payment_by_interval['05']['dollar']
                + $totals_safact_by_interval['bolivarToDollar'] 
                + $totals_safact_by_interval['creditoToDollar'];

            $total = $total_dollars + $total_bs_to_dollars;

            $pdf = App::make('dompdf.wrapper');
            
            $view_name = 'pdf.money-entrance.money_record';
        
            $pdf = $pdf->loadView($view_name, compact(
                    'totals_e_payment',
                    'totals_safact',
                    'totals_e_payment_by_user',
                    'totals_safact_by_user',
                    'currency_signs',
                    'start_date',
                    'end_date',
                    'totals_safact_by_interval',
                    'totals_e_payment_by_interval',
                    'total_dollars',
                    'total_bs_to_dollars',
                    'total'
                ))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->download('entrada_dinero'  . '_' . $start_date . '.pdf');
        }

    }

}
