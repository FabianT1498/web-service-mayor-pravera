<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\App;

use App\Repositories\BillRepository;
use App\Exports\VueltosFacturasExport;

class BillController extends Controller
{
    private $flasher = null;

    public function index(){
        $start_date = Carbon::now()->format('d-m-Y');
        $end_date = $start_date;
        return view('pages.vales-vueltos-facturas.index', compact('start_date', 'end_date'));
    }

    private function getTotalVueltos($total_bill_vuelto_by_user){
        return $total_bill_vuelto_by_user->reduce(function($acc, $item){
            $acc['MontoBs'] += $item->first()->MontoBs;
            $acc['MontoDiv'] += $item->first()->MontoDiv;

            return $acc;
        }, ['MontoBs' => 0, 'MontoDiv' => 0]);
    }

    private function getBillData($new_start_date, $new_finish_date, BillRepository $bill_repo){
        $bill_vueltos = $bill_repo
            ->getValesAndVueltos($new_start_date, $new_finish_date)
            ->groupBy(['CodUsua', 'FechaE']);

        $total_bill_vales_vueltos_by_user = $bill_repo
            ->getTotalValesAndVueltosByUser($new_start_date, $new_finish_date)
            ->groupBy(['CodUsua']);

        $total_bill_vueltos = $this->getTotalVueltos($total_bill_vales_vueltos_by_user);
 
        return compact([
            'bill_vueltos',
            'total_bill_vales_vueltos_by_user',
            'total_bill_vueltos',
        ]);
    }

    public function generatePDF(Request $request, BillRepository $bill_repo){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $file_name = 'Detalles_vales_vueltos' . ($new_start_date === $new_finish_date 
                ? $start_date 
                : 'desde_' . $start_date . '_hasta_' . $end_date
                )
                . '.pdf';

            $data = $this->getBillData($new_start_date, $new_finish_date, $bill_repo);
            
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;

            $pdf = App::make('dompdf.wrapper');
            
            $view_name = 'pdf.bill.vueltos-vales-report';

            $name = 'vueltos_vales_fac_' . ($new_start_date === $new_finish_date 
                ? $start_date 
                : $start_date . 'hasta' . $end_date
                )
                . '.pdf';

            $pdf = $pdf->loadView($view_name, $data)
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

            return $pdf->stream($name);
        }
    }

    public function generateExcel(Request $request, BillRepository $bill_repo){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $data = $this->getBillData($new_start_date, $new_finish_date, $bill_repo);
            
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
                
            $file_name = 'Vueltos_Facturas_' . ($new_start_date === $new_finish_date 
                ? $start_date 
                : 'desde_' . $start_date . '_hasta_' . $end_date
                )
                . '.xlsx';

            return Excel::download(new VueltosFacturasExport($data), $file_name);
        }
    }
}
