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

    private function getTotalsByInterval($start_date, $end_date){

        $totals_cash = DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw('COALESCE(CAST(ROUND(SUM(SAFACT.Monto), 2) AS decimal(18,2)), 0) AS total,
                MAX(SAFACT.TipoFac) AS TipoFac, MAX(SAFACT.CodUsua) AS CodUsua,
                MAX(CAST(SAFACT.FechaE as date)) AS FechaE')
            ->leftJoin('SAIPAVTA', 'SAIPAVTA.NumeroD', '=', 'SAFACT.NumeroD')
            ->whereRaw("SAIPAVTA.NumeroD IS NULL AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5', 'CAJA6' , 'CAJA7', 'DELIVERY')
                    AND CAST(SAFACT.FechaE AS date) BETWEEN CAST(:start_date AS date) AND CAST(:end_date AS date)",
                    [
                        'start_date' => $start_date,
                        'end_date' => $end_date
                    ]
            )
            ->groupByRaw("SAFACT.TipoFac, CAST(SAFACT.FechaE AS date), SAFACT.CodUsua")
            ->orderByRaw("MAX(CAST(SAFACT.FechaE as date)) asc")
            ->get();

        $totals_e_payment = DB
            ::connection('saint_db')
            ->table('SAIPAVTA')
            ->selectRaw('COALESCE(CAST(ROUND(SUM(SAIPAVTA.Monto), 2) AS decimal(18,2)), 0) as total,
                MAX(SAIPAVTA.TipoFac) AS TipoFac, MAX(SAIPAVTA.CodPago) AS CodPago,
                MAX(SAFACT.CodUsua) as CodUsua, MAX(CAST(SAFACT.FechaE as date)) as FechaE')
            ->join('SAFACT', function($query) use ($start_date, $end_date){
                $query
                ->on('SAIPAVTA.NumeroD', '=', 'SAFACT.NumeroD ')
                ->whereRaw("SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4',
                    'CAJA5', 'CAJA6', 'CAJA7', 'CAJA8', 'DELIVERY')")
                ->whereRaw("CAST(SAFACT.FechaE AS date) BETWEEN CAST('2022-03-23' as date)
                    AND CAST('2022-03-25' as date)");
            })
            ->groupByRaw("SAIPAVTA.CodPago, SAIPAVTA.TipoFac, CAST(SAFACT.FechaE AS date), SAFACT.CodUsua")
            ->orderByRaw("MAX(SAFACT.CodUsua) asc, MAX(CAST(SAIPAVTA.FechaE as date)) asc")
            ->get();

        return compact('totals_cash', 'totals_e_payment');
    }

    private function getTotalsByDateFromSaint($date){
        $totals_cash = DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw('COALESCE(CAST(ROUND(SUM(SAFACT.Monto), 2) AS decimal(18,2)), 0) as total,
		        MAX(SAFACT.TipoFac) AS TipoFac, MAX(SAFACT.CodUsua) as CodUsua')
            ->leftJoin('SAIPAVTA', 'SAIPAVTA.NumeroD', '=', 'SAFACT.NumeroD')
            ->whereRaw("SAIPAVTA.NumeroD IS NULL AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5', 'CAJA6' , 'CAJA7', 'DELIVERY')
                AND CAST(SAFACT.FechaE AS date) = CAST(? as date)", [$date])
            ->groupBy(['SAFACT.TipoFac', 'SAFACT.CodUsua'])
            ->orderByRaw("SAFACT.CodUsua asc, MAX(CAST(SAFACT.FechaE AS date)) asc")
            ->get()
            ->groupBy('CodUsua');

        $totals_e_payment = DB
            ::connection('saint_db')
            ->table('SAIPAVTA')
            ->selectRaw('COALESCE(CAST(ROUND(SUM(SAIPAVTA.Monto), 2) AS decimal(18,2)), 0) as total,
                MAX(SAIPAVTA.TipoFac) AS TipoFac, MAX(SAIPAVTA.CodPago) AS CodPago,
                MAX(SAFACT.CodUsua) as CodUsua')
            ->join('SAFACT', 'SAIPAVTA.NumeroD', '=', 'SAFACT.NumeroD')
            ->whereRaw("SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4','CAJA5', 'CAJA6',
                'CAJA7', 'CAJA8', 'DELIVERY') AND CAST(SAFACT.FechaE AS date) = CAST(? as date)", [$date])
            ->groupBy(['SAIPAVTA.CodPago', 'SAIPAVTA.TipoFac', 'SAFACT.CodUsua'])
            ->orderByRaw("SAFACT.CodUsua asc, MAX(CAST(SAFACT.FechaE AS date)) asc")
            ->get()
            ->groupBy('CodUsua');

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
