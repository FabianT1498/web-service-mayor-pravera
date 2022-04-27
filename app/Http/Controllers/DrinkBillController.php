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

class DrinkBillController extends Controller
{
    private $flasher = null;

    const DEFAULT_USER = 'ALL';

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    private function getFiscalBillsWithDrinksFromSaint($start_date, $finish_date, $user = self::DEFAULT_USER){

        $queryParams = ($start_date === $finish_date) ? [$start_date] : [$start_date, $finish_date]; 

        /* Consulta para obtener las facturas con sus elementos relacionados y su precio de venta */
        $data = DB
            ::connection('saint_db')
            ->table('SAITEMFAC')
            ->selectRaw("SAITEMFAC.NumeroD AS NumeroD, SAPROD.Descrip AS Descrip, CAST(SAITEMFAC.Cantidad AS int) as Cantidad,
                CAST(CONVERT(VARCHAR, CAST(SAITEMFAC.Precio * SAFACT.Signo AS MONEY), 1) AS VARCHAR) as PrecioVentaUnidad,
                CAST(CONVERT(VARCHAR, CAST(CAST(ROUND((SAITEMFAC.Precio * SAFACT.Signo * SAITEMFAC.Cantidad), 2) AS decimal(18,2)) AS MONEY), 1) AS VARCHAR) as Subtotal,
                SAFACT.CodUsua as CodUsua, CAST(SAITEMFAC.FechaE as date) as FechaE")
            ->join('SAPROD', function($join){
                $join
                    ->on('SAITEMFAC.CodItem', '=', 'SAPROD.CodProd')
                    ->whereRaw('SAPROD.CodInst IN (1664, 1653)');
            })
            ->join('SAFACT', function($join) use ($user){
                $queryParams = $user !== self::DEFAULT_USER ? [$user] : [];
                $join
                    ->on('SAITEMFAC.NumeroD', '=', 'SAFACT.NumeroD')
                    ->whereRaw("SAFACT.EsNF = 0 AND " . ($user !== self::DEFAULT_USER
                        ? "SAFACT.CodUsua = ?" 
                        : "SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5', 'CAJA6' , 'CAJA7', 'DELIVERY')"),
                    $queryParams);
            })
            ->whereRaw(($start_date === $finish_date) 
                ? "CAST(SAITEMFAC.FechaE as date) = ?"
                : "CAST(SAITEMFAC.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)",
                $queryParams)
            ->orderByRaw("SAFACT.CodUsua ASC, CAST(SAITEMFAC.FechaE as date) ASC")
            ->get();

     
            $data = $data->groupBy(['CodUsua', 'FechaE']);
        
            
        return $data;
    }

    private function getTotalsFiscalBillsWithDrinksFromSaint($start_date, $finish_date, $user = self::DEFAULT_USER){
        /* Consulta para obtener los totales de las facturas*/
        $queryParams = ($start_date === $finish_date) ? [$start_date] : [$start_date, $finish_date]; 

        return DB
            ::connection('saint_db')
            ->table('SAITEMFAC')
            ->selectRaw("CAST(ROUND(SUM(SAITEMFAC.Precio * SAFACT.Signo * SAITEMFAC.Cantidad), 2) AS decimal(18,2)) as total,
                CAST(SAITEMFAC.FechaE AS date) AS FechaE, MAX(SAFACT.CodUsua) AS CodUsua")
            ->join('SAPROD', function($join){
                $join
                    ->on('SAITEMFAC.CodItem', '=', 'SAPROD.CodProd')
                    ->whereRaw('SAPROD.CodInst IN (1664, 1653)');
            })
            ->join('SAFACT', function($join) use ($user){
                $queryParams = $user !== self::DEFAULT_USER ? [$user] : [];
                $join
                    ->on('SAITEMFAC.NumeroD', '=', 'SAFACT.NumeroD')
                    ->whereRaw("SAFACT.EsNF = 0 AND " . ($user !== self::DEFAULT_USER
                        ? "SAFACT.CodUsua = ?" 
                        : "SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5', 'CAJA6' , 'CAJA7', 'DELIVERY')"),
                    $queryParams);
            })
            ->whereRaw(($start_date === $finish_date) 
                ? "CAST(SAITEMFAC.FechaE as date) = ?"
                : "CAST(SAITEMFAC.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)",
                $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAITEMFAC.FechaE AS date)")
            ->orderByRaw("MAX(SAFACT.CodUsua) ASC, MAX(CAST(SAITEMFAC.FechaE as date)) ASC")
            ->get()
            ->groupBy(['CodUsua', 'FechaE']);
    }

    private function getCashRegisterUsers(){
       $result = DB::table('cash_register_users')
        ->select([
            'cash_register_users.name as user',
        ])
        ->get();

        return $result
            ->map(function($item, $key) {
                return (object) array("key" => $item->user, "value" => $item->user);
            });
    }

    private function getTotalsFiscalBillsWithDrinksByUser($array){
        $totals = [];

        foreach($array as $key => $child){
            $total = $child->reduce(function ($carry, $item) {
                return $carry + $item[0]->total;
            });

            $totals[$key] = $total;
        }

        return $totals;
    }

    public function index(){
        $today_date = Carbon::now()->format('d-m-Y');

        $cash_register_users = $this->getCashRegisterUsers();
        $cash_register_users->prepend((object)["key" => self::DEFAULT_USER, "value" => "Todos"]);
        $default_user = self::DEFAULT_USER;

        return view('pages.fiscal-bill.drink-bills-index', compact('today_date',
            'cash_register_users', 'default_user'));
    }

    public function generateReport(Request $request){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');
        $cash_register_user = $request->query('cash_register_user', '');

        $currency_signs = [
            'dollar' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')),
            'bs' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR'))
        ];

        if ($start_date && $end_date){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_end_date = date('Y-m-d', strtotime($end_date));

            $pdf = App::make('dompdf.wrapper');

            $data = $this->getFiscalBillsWithDrinksFromSaint($new_start_date,
                $new_end_date, $cash_register_user);

            $totals = $this->getTotalsFiscalBillsWithDrinksFromSaint($new_start_date,
            $new_end_date, $cash_register_user);

            $totals_by_user = $this->getTotalsFiscalBillsWithDrinksByUser($totals);

            $view_name = 'pdf.fiscal-bill.alcoholic-drinks';
        
            $pdf = $pdf->loadView($view_name, compact(
                    'data',
                    'currency_signs',
                    'start_date',
                    'end_date',
                    'totals',
                    'totals_by_user'
                ))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->stream('facturas_fiscales'  . '_' . $start_date . '.pdf');
        }

    }
}
