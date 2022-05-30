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

    private function getTotalVueltosByUser($total_bill_vuelto_by_user){
        $total = [];

        foreach($total_bill_vuelto_by_user as $key_user => $dates){
            $total[$key_user] = [];
            foreach($dates as $vueltos){
                foreach($vueltos as $key_method_vuelto => $vuelto){
                    if (!array_key_exists($key_method_vuelto, $total[$key_user])){
                        $total[$key_user][$key_method_vuelto] = [];
                        $total[$key_user][$key_method_vuelto]['MontoBs'] = 0;
                        $total[$key_user][$key_method_vuelto]['MontoDiv'] = 0;
                    }

                    $total[$key_user][$key_method_vuelto]['MontoBs'] += $vuelto->first()->MontoBs;
                    $total[$key_user][$key_method_vuelto]['MontoDiv'] += $vuelto->first()->MontoDiv;
                }
            }
        }

        return $total;
    }

    private function getTotalVueltos($total_money_back_by_users){
        $total = [];

        foreach($total_money_back_by_users as $vueltos){
            foreach($vueltos as $key_method_vuelto => $record){
                if (!array_key_exists($key_method_vuelto, $total)){
                    $total[$key_method_vuelto] = [];
                    $total[$key_method_vuelto]['MontoBs'] = 0;
                    $total[$key_method_vuelto]['MontoDiv'] = 0;
                }

                $total[$key_method_vuelto]['MontoBs'] += $record['MontoBs'];
                $total[$key_method_vuelto]['MontoDiv'] += $record['MontoDiv'];
            }
        }

        return $total;
    }

    private function getBillData($new_start_date, $new_finish_date, BillRepository $bill_repo){
        $bill_vueltos = $bill_repo
            ->getValesAndVueltos($new_start_date, $new_finish_date)
            ->groupBy(['CodUsua', 'FechaE', 'NumeroD', 'FactUso']);
    
        $money_back_by_users = $bill_repo
            ->getTotalValesAndVueltosByUser($new_start_date, $new_finish_date)
            ->groupBy(['CodUsua', 'FechaE', 'FactUso']);

        $total_money_back_by_users = $this->getTotalVueltosByUser($money_back_by_users);

        $total_money_back = $this->getTotalVueltos($total_money_back_by_users);
 
        return compact([
            'bill_vueltos',
            'money_back_by_users',
            'total_money_back_by_users',
            'total_money_back',
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

    public function getMoneyBackByCashRegisterUserSaint(BillRepository $bill_repo, $user, $start_date, $end_date){
        $money_back_by_user = $bill_repo
            ->getTotalValesAndVueltosByUser($start_date, $end_date, $user)
            ->groupBy(['FactUso']);

        return $this->jsonResponse(['data' =>  $money_back_by_user ], 200);
    }


}
