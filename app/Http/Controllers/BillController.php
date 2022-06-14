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
            $total[$key_user]['MontoDivEfect'] = 0;
            $total[$key_user]['MontoBsEfect'] = 0;
            $total[$key_user]['MontoDivPM'] = 0;
            $total[$key_user]['MontoBsPM'] = 0;

            foreach($dates as $vuelto){
                $total[$key_user]['MontoDivEfect'] += $vuelto->first()->MontoDivEfect;
                $total[$key_user]['MontoBsEfect'] += $vuelto->first()->MontoBsEfect;
                $total[$key_user]['MontoDivPM'] += $vuelto->first()->MontoDivPM;
                $total[$key_user]['MontoBsPM'] += $vuelto->first()->MontoBsPM;
            }
        }

        return $total;
    }

    private function getTotalVueltos($total_money_back_by_users){
        $total = [];
        $total['MontoDivEfect'] = 0;
        $total['MontoBsEfect'] = 0;
        $total['MontoDivPM'] = 0;
        $total['MontoBsPM'] = 0;


        foreach($total_money_back_by_users as $vueltos){
            $total['MontoDivEfect'] += $vueltos['MontoDivEfect'];
            $total['MontoBsEfect'] += $vueltos['MontoBsEfect'];
            $total['MontoDivPM'] += $vueltos['MontoDivPM'];
            $total['MontoBsPM'] += $vueltos['MontoBsPM'];
        }

        return $total;
    }

    private function getBillData($new_start_date, $new_finish_date, BillRepository $bill_repo){
        $bill_vueltos = $bill_repo
            ->getVueltos($new_start_date, $new_finish_date)
            ->groupBy(['CodUsua', 'FechaE', 'NumeroD']);

        $bill_vueltos_by_user_date = $bill_repo
            ->getVueltosByUser($new_start_date, $new_finish_date)
            ->groupBy(['CodUsua', 'FechaE']);

        $total_vuelto_by_user = $this->getTotalVueltosByUser($bill_vueltos_by_user_date);
        $total_vuelto = $this->getTotalVueltos($total_vuelto_by_user);

        return compact([
            'bill_vueltos',
            'bill_vueltos_by_user_date',
            'total_vuelto_by_user',
            'total_vuelto',
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
