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

use App\Repositories\CashRegisterRepository;

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

    private function getTotalIvaFromSafact($start_date, $end_date){
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
            ->selectRaw("MAX(SAFACT.EsNF) as EsNF,
                CAST(ROUND(SUM(SAFACT.MtoTax * SAFACT.Signo), 2) AS decimal(18, 2))  AS iva,
                CAST(ROUND(SUM(SAFACT.MtoTax * SAFACT.Signo)/MAX(FactorHist.MaxFactor), 2) AS decimal(18, 2))  AS ivaDolares,
                CAST(ROUND(SUM((SAFACT.TGravable + SAFACT.TExento) * SAFACT.Signo), 2) AS decimal(18, 2))  AS baseImponible,
                CAST(ROUND((SUM((SAFACT.TGravable + SAFACT.TExento) * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))  AS baseImponibleADolares")  
            ->joinSub($factors, 'FactorHist', function($query){
                $query->on(DB::raw("CAST(SAFACT.FechaE AS date)"), '=', "FactorHist.FechaE");
            })
            ->whereRaw("SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.EsNF")
            ->get()
            ->groupBy(['EsNF']);
    }

    private function getCountTypeBills($start_date, $end_date){

        /* Consulta para obtener la cantidad de facturas */
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("COUNT(SAFACT.NumeroD) as CantidadRegistros")  
            ->whereRaw("SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.EsNF")
            ->get()
            ->groupBy(['EsNF']);
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

    // Metodo para agregar los metodos de pagos electronicos en cajas que no tuvieron ningun movimiento
    // de este tipo, para un intervalo de tiempo.
    private function completeEpaymentMethodsToCashUserForInteval($totals_from_safact, $totals_e_payment, $payment_methods){
        $default_e_payment_values = $payment_methods->keys()->reduce(function($carry, $item){
            $carry[$item] = [];
            $carry[$item]['bs'] = 0.00;
            $carry[$item]['dollar'] = 0.00;

            return $carry;
        }, []);

        foreach($totals_from_safact as $key_user => $dates){
            if (array_key_exists($key_user, $totals_e_payment)){
                foreach ($dates as $key_date => $date){ 
                    if (!array_key_exists($key_date, $totals_e_payment[$key_user])){
                        $totals_e_payment[$key_user][$key_date] = $default_e_payment_values;
                    }
                }
            } else {
                $totals_e_payment[$key_user] = [];
                foreach ($dates as $key_date => $date){
                    $totals_e_payment[$key_user][$key_date] = $default_e_payment_values;
                }
            }
        }

        return $totals_e_payment;
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

    private function getTotalByDate($total_from_safact, $total_e_payment){
        $total = [];
        foreach($total_from_safact as $cod_usua => $dates){
            foreach($dates as $key_date => $record){    
                if (!key_exists($key_date, $total)){
                    $total[$key_date] = [];
                    $total[$key_date]['dollar'] = 0;
                    $total[$key_date]['bs'] = 0;
                }

                $total[$key_date]['dollar'] += $total_e_payment[$cod_usua][$key_date]['07']['dollar'] 
                    + $total_e_payment[$cod_usua][$key_date]['08']['dollar'] + $record->first()->dolares;

                $total[$key_date]['bs'] += $total_e_payment[$cod_usua][$key_date]['01']['dollar'] 
                    + $total_e_payment[$cod_usua][$key_date]['02']['dollar']  
                    + $total_e_payment[$cod_usua][$key_date]['03']['dollar'] 
                    + $total_e_payment[$cod_usua][$key_date]['04']['dollar'] 
                    + $total_e_payment[$cod_usua][$key_date]['05']['dollar'] 
                    + $record->first()->bolivaresADolares
                    + $record->first()->creditoADolares;

                $total[$key_date]['subtotal'] = $total[$key_date]['dollar'] + $total[$key_date]['bs'];
            }
        }

        return $total;
    }

    public function generateReport(CashRegisterRepository $cash_register_repo, Request $request){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        $currency_signs = [
            'dollar' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')),
            'bs' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR'))
        ];

        if ($start_date && $end_date){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $totals_from_safact = $cash_register_repo
                ->getTotalsFromSafact($start_date, $end_date)
                ->groupBy(['CodUsua', 'FechaE']);

          
            $payment_methods = $this->getPaymentMethods();

            $totals_e_payment = $cash_register_repo
                ->getTotalsEPaymentMethods($start_date, $end_date)
                ->groupBy(['CodUsua', 'FechaE']);

            // Completar los metodos de pagos que no tuvieron registros en cada caja
            $totals_e_payment  = $this
                ->mapEPaymentMethods($totals_e_payment, $payment_methods);

            // Completar las cajas con sus respectivos metodos de pago que no tuvieron operacion con pagos electronicos
            $totals_e_payment = $this->completeEpaymentMethodsToCashUserForInteval($totals_from_safact, $totals_e_payment, $payment_methods);

            $totals_e_payment_by_user = $this->getTotalEpaymentByUser($totals_e_payment, $payment_methods);

            $totals_safact_by_user = $this->getTotalFromSafactByUser($totals_from_safact);

            $totals_safact_by_interval = $this->getTotalFromSafactByInterval($totals_safact_by_user);

            $totals_e_payment_by_interval = $this->getTotalEPaymentByInterval($totals_e_payment_by_user);

            $totals_iva = $this->getTotalIvaFromSafact($new_start_date, $new_finish_date);

            return print_r($this->getCountTypeBills($new_start_date, $new_finish_date));

            $total_iva_dollar = $totals_iva[0][0]->ivaDolares + $totals_iva[1][0]->ivaDolares;

            $total_iva_bs = $totals_iva[0][0]->iva + $totals_iva[1][0]->iva;

            $total_base_imponible_bs =  $totals_iva[0][0]->baseImponible + $totals_iva[1][0]->baseImponible;

            $total_base_imponible_dollar =  $totals_iva[0][0]->baseImponibleADolares + $totals_iva[1][0]->baseImponibleADolares;

            $total_by_date = $this->getTotalByDate($totals_from_safact, $totals_e_payment);

            // return print_r($totals_from_safact);

           // Resumen de entrada de dinero
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
                    'totals_from_safact',
                    'totals_e_payment_by_user',
                    'totals_safact_by_user',
                    'currency_signs',
                    'start_date',
                    'end_date',
                    'totals_safact_by_interval',
                    'totals_e_payment_by_interval',
                    'total_dollars',
                    'total_bs_to_dollars',
                    'total_by_date',
                    'total',
                    'totals_iva',
                    'total_iva_dollar',
                    'total_iva_bs',
                    'total_base_imponible_bs',
                    'total_base_imponible_dollar'
                ))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->stream('entrada_dinero'  . '_' . $start_date . '.pdf');
        }

    }

}
