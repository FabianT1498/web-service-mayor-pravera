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

class ZBillController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(){
        $start_date = Carbon::now()->format('d-m-Y');
        $end_date = $start_date;
        return view('pages.z-bill.index', compact('start_date', 'end_date'));
    }

    private function getTotalExento($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE as date) as FechaE, MAX(SAFACT.NumeroP) as NumeroP,
                 MAX(SAFACT.NumeroZ) as NumeroZ, CAST(SUM(SAFACT.TExento * SAFACT.Signo) AS decimal(10,2)) as ventaTotalExenta")  
            ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date), SAFACT.NumeroP, SAFACT.NumeroZ")
            ->get()
            ->groupBy(['CodUsua', 'FechaE', 'NumeroP', 'NumeroZ']);
    }

    private function getTotalLicores($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/       
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAITEMFAC.FechaE as date) = ?"
            : "CAST(SAITEMFAC.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAITEMFAC')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE as date) as FechaE, MAX(SAFACT.NumeroP) as NumeroP,
                MAX(SAFACT.NumeroZ) as NumeroZ, CAST(SUM(SAITEMFAC.Cantidad * SAITEMFAC.Precio * SAFACT.Signo) AS decimal(10,2)) as ventaLicoresBS")
            ->join('SAPROD', function($query){
                $query
                    ->on("SAITEMFAC.CodItem", '=', "SAPROD.CodProd")
                    ->whereRaw("SAPROD.CodInst IN (1664, 1653)");
            })
            ->join('SAFACT', function($query){
                $query
                    ->on("SAFACT.NumeroD", '=', "SAITEMFAC.NumeroD")
                    ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                    'CAJA6' , 'CAJA7', 'DELIVERY')");
            })
            ->whereRaw("SAITEMFAC.EsExento = 1 AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date), SAFACT.NumeroP, SAFACT.NumeroZ")
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date) asc")
            ->get()
            ->groupBy(['CodUsua', 'FechaE', 'NumeroP', 'NumeroZ']);

    }

    private function getBaseImponibleByTax($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];
        
        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
        ::connection('saint_db')
        ->table('SAFACT')
        ->selectRaw("MAX(SAFACT.CodUsua) AS CodUsua, CAST(SAFACT.FechaE as date) as FechaE, MAX(SAFACT.NumeroP) as NumeroP, MAX (SAFACT.NumeroZ) as NumeroZ,
            MAX(SATAXVTA.CodTaxs) as CodTaxs, SUM(SATAXVTA.TGravable * SAFACT.Signo) as TGravable")
        ->join('SATAXVTA', function($query){
            $query
                ->on("SATAXVTA.NumeroD", '=', "SAFACT.NumeroD");
        })
        ->whereRaw("SAFACT.EsNF = 0 AND SATAXVTA.CodTaxs IN ('IVA', 'IVA8') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
            'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
        ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date), SAFACT.NumeroP, SAFACT.NumeroZ, SATAXVTA.CodTaxs")
        ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date), SATAXVTA.CodTaxs desc")
        ->get()
        ->groupBy(['CodUsua', 'FechaE', 'NumeroP', 'NumeroZ']);
    }

    // Método para completar los métodos de pagos que no han tenido ingresos
    // por cada caja en cada fecha.
    private function mapTaxes($array, $iva_codes){

        $total_base_imponible = [];

        foreach($array as $cod_usua_key => $dates){
            $total_base_imponible[$cod_usua_key] = [];
            // Iterating over every date of cash register user
            foreach ($dates as $date_key => $printers){
                $total_base_imponible[$cod_usua_key][$date_key] = [];
                foreach ($printers as $key_printer => $z_numbers){
                    $total_base_imponible[$cod_usua_key][$date_key][$key_printer] = [];
                    foreach ($z_numbers as $key_z => $record){
                        $total_base_imponible[$cod_usua_key][$date_key][$key_printer][$key_z] = [];
                        // Mapping every total with its key
                        foreach($iva_codes as $index => $cod_iva){
                            $data = $record->slice($index, 1)->first();
        
                            if (!is_null($data)){
                                if ($cod_iva === $data->CodTaxs){
                                    $total_base_imponible[$cod_usua_key][$date_key][$key_printer][$key_z][$cod_iva] = $data->TGravable;
                                } else if (in_array($data->CodTaxs, $iva_codes)) {
                                    if (!key_exists($cod_iva, $total_base_imponible[$cod_usua_key][$date_key][$key_printer][$key_z])){
                                        $total_base_imponible[$cod_usua_key][$date_key][$key_printer][$key_z][$cod_iva] = 0.00;
                                    }
                                    $total_base_imponible[$cod_usua_key][$date_key][$key_printer][$key_z][$data->CodTaxs] = $data->TGravable;
                                }
                            } else if (!key_exists($cod_iva,  $total_base_imponible[$cod_usua_key][$date_key][$key_printer][$key_z])) {
                                $total_base_imponible[$cod_usua_key][$date_key][$key_printer][$key_z][$cod_iva] = 0.00;
                            }
                        }
                    }
                }
            }
        }
        

        return $total_base_imponible;
    }

    public function generateReport(Request $request){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $iva_codes = ['IVA', 'IVA8'];

            $total_exento = $this->getTotalExento($new_start_date, $new_finish_date);
            $total_licores = $this->getTotalLicores($new_start_date, $new_finish_date);
            $total_base_imponible_by_tax = $this->getBaseImponibleByTax($new_start_date, $new_finish_date);
            $total_base_imponible_by_tax = $this->mapTaxes($total_base_imponible_by_tax, $iva_codes);

            return print_r( $total_base_imponible_by_tax);

            $pdf = App::make('dompdf.wrapper');
            
            $view_name = 'pdf.z-bill.z_bill_summary';

        
            $pdf = $pdf->loadView($view_name, compact(
                    'total_exento',
                    'total_licores',
                    'total_base_imponible_by_tax',
                    'start_date', 
                    'end_date'
                ))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->stream('facturas_z'  . '_' . $start_date . '.pdf');
        }

    }

}
