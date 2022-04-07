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

    private function getTotalsMoneyEntrance($start_date, $end_date){
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

        $totals_cash = DB
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

        

        $totals_e_payment = DB
            ::connection('saint_db')
            ->table('SAIPAVTA')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, MAX(SAIPAVTA.CodPago) as CodPago, MAX(CAST(SAFACT.FechaE as date)) as FechaE,
            CAST(ROUND(SUM(SAIPAVTA.Monto * SAFACT.Signo), 2) AS decimal(18, 2)) as total")
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

        return $totals_e_payment;
        
        return compact('totals_cash', 'totals_e_payment');
    }

    public function index(){
        $start_date = Carbon::now()->format('d-m-Y');
        $end_date = $start_date;
        return view('pages.money-entrance.index', compact('start_date', 'end_date'));
    }

    private function mapSaintTotalsByDate($new_start_date){
        $cash_register_totals = $this->getTotalsByDateFromSaint($new_start_date);

        $totals_saint = [];

        // Iterating every cash register user
        foreach($cash_register_totals['totals_cash']->keys() as $cod_usua){

            // Mapping every cash record entry with its key
            foreach(array_keys(config('constants.CASH_TIPO_FAC')) as $index => $value){
                $record = $cash_register_totals['totals_cash'][$cod_usua]->slice($index, 1)->first();
                $key = config('constants.CASH_TIPO_FAC.'. $value);

                if (!is_null($record)){
                    if ($value === $record->TipoFac){
                        $totals_saint[$cod_usua][$key] = $record->total;
                    } else {
                        if (!key_exists($key, $totals_saint[$cod_usua])){
                            $totals_saint[$cod_usua][$key] = 0;
                        }
                        $totals_saint[$cod_usua][config('constants.CASH_TIPO_FAC.'. $record->TipoFac)] = $record->total;
                    }
                } else if (!key_exists($key, $totals_saint)) {
                    $totals_saint[$cod_usua][$key] = 0;
                }
            }
        }

        // Iterating every cash register user
        foreach($cash_register_totals['totals_e_payment']->keys() as $cod_usua){

            // Mapping every cash record entry with its key
            foreach(array_keys(config('constants.COD_PAGO')) as $index => $value){
                $record = $cash_register_totals['totals_e_payment'][$cod_usua]->slice($index, 1)->first();
                $key = config('constants.COD_PAGO.'. $value);

                if (!is_null($record)){
                    if ($value === $record->CodPago){
                        $totals_saint[$cod_usua][$key] = $record->total;
                    } else {
                        if (!key_exists($key, $totals_saint[$cod_usua])){
                            $totals_saint[$cod_usua][$key] = 0;
                        }
                        $totals_saint[$cod_usua][config('constants.COD_PAGO.'. $record->CodPago)] = $record->total;
                    }
                } else if (!key_exists($key, $totals_saint[$cod_usua])) {
                    $totals_saint[$cod_usua][$key] = 0;
                }
            }
        }

        return $totals_saint;
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

            return print_r($this->getTotalsMoneyEntrance($new_start_date, $new_finish_date));

            $pdf = App::make('dompdf.wrapper');

            $view_name = '';
            $data = null;

            if ($new_start_date === $new_finish_date){
                $data = $this->mapSaintTotalsByDate($new_start_date);
                $view_name = 'pdf.money-entrance.single-date';
            } else {
                $view_name = 'pdf.money_entrance.interval-record';
            }

            $pdf = $pdf->loadView($view_name, compact(
                    'data',
                    'currency_signs',
                    'start_date'
                ))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                ]);

            return $pdf->download('entrada_dinero'  . '_' . $start_date . '.pdf');
        }

    }

}
