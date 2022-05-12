<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;


use App\Exports\ZBillExport;
use App\Repositories\ZBillRepository;

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

    private function getTotalsByUser($totals_from_safact, $total_licores, $total_base_imponible_by_tax){
        $totals = [];

        foreach($totals_from_safact as $key_codusua => $dates){
            $totals[$key_codusua] = [];
            $totals[$key_codusua]['total_IVA'] = 0;
            $totals[$key_codusua]['base_imponible_16'] = 0;
            $totals[$key_codusua]['IVA_16'] = 0; 
            $totals[$key_codusua]['base_imponible_8'] = 0;
            $totals[$key_codusua]['IVA_8'] = 0;
            $totals[$key_codusua]['total_exento'] = 0;
            $totals[$key_codusua]['total_licores'] = 0;
            $totals[$key_codusua]['total_viveres'] = 0;

            // Iterating over every date of cash register user
            foreach ($dates as $key_date => $printers){
                foreach ($printers as $key_printer => $z_numbers){
                    foreach ($z_numbers as $key_z_number => $record){
                        
                        $totals[$key_codusua]['total_IVA'] += $record->first()->ventaTotalIVA;

                        if(count($total_base_imponible_by_tax) > 0 && key_exists($key_codusua, $total_base_imponible_by_tax)
                                && key_exists($key_date, $total_base_imponible_by_tax[$key_codusua])
                                    && key_exists($key_printer, $total_base_imponible_by_tax[$key_codusua][$key_date])){
                            $totals[$key_codusua]['base_imponible_16'] += $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'];
                            $totals[$key_codusua]['IVA_16'] += ($total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'] * 0.16); 
                            $totals[$key_codusua]['base_imponible_8'] += $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'];
                            $totals[$key_codusua]['IVA_8'] += ($total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'] * 0.08);
                        }
                        
                        $totals[$key_codusua]['total_exento'] += $record->first()->ventaTotalExenta;
                        
                        if($total_licores->count() > 0 && $total_licores->has($key_codusua)
                                && $total_licores[$key_codusua]->has($key_date) && $total_licores[$key_codusua][$key_date]->has($key_printer)){
                            $totals[$key_codusua]['total_licores'] += $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS;
                            $totals[$key_codusua]['total_viveres'] += ($record->first()->ventaTotalExenta - $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS);
                        } else {
                            $totals[$key_codusua]['total_viveres'] += $record->first()->ventaTotalExenta;
                        }
                    }
                }
            }
        }
        

        return $totals;
    }

    private function getTotals($totals_by_user){
        $total_general = [];
        $total_general['total_IVA'] = 0;
        $total_general['base_imponible_16'] = 0;
        $total_general['IVA_16'] = 0; 
        $total_general['base_imponible_8'] = 0;
        $total_general['IVA_8'] = 0;
        $total_general['total_exento'] = 0;
        $total_general['total_licores'] = 0;
        $total_general['total_viveres'] = 0;

        foreach($totals_by_user as $key_codusua => $totals){
            foreach($totals as $total_key => $total){
                $total_general[$total_key] += $total;
            }
        }

        return $total_general;
    }

    private function getZBillData($start_date, $end_date, ZBillRepository $repo){
        $iva_codes = ['IVA', 'IVA8'];

        $totals_from_safact = $repo->getTotalsFromSafact($start_date, $end_date);
        $total_licores = $repo->getTotalLicores($start_date, $end_date);
        $total_base_imponible_by_tax = $repo->getBaseImponibleByTax($start_date, $end_date);

        $total_base_imponible_by_tax = $this->mapTaxes($total_base_imponible_by_tax, $iva_codes);

        $totals_by_user = $this->getTotalsByUser($totals_from_safact, $total_licores,  $total_base_imponible_by_tax);

        $total_general = $this->getTotals($totals_by_user);

        return compact(
            'totals_from_safact',
            'total_licores',
            'total_base_imponible_by_tax',
            'totals_by_user',
            'total_general'
        );
    }

    public function generatePDF(Request $request, ZBillRepository $repo){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));
            
            $data = $this->getZBillData($new_start_date, $new_finish_date, $repo);
            
            $pdf = App::make('dompdf.wrapper');
            
            $view_name = 'pdf.z-bill.z_bill_summary';
            
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;

            $pdf = $pdf->loadView($view_name, $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->stream('facturas_z'  . '_' . $start_date . '.pdf');
        }

    }

    public function generateExcel(Request $request, ZBillRepository $repo){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $data = $this->getZBillData($new_start_date, $new_finish_date, $repo);

            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;

            return Excel::download(new ZBillExport($data), 'safact_totals.xlsx');
        }
    }
}
