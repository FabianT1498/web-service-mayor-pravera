<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;

use App\Repositories\CashRegisterRepository;
use App\Exports\ZelleRecordsExport;

class ZelleReportController extends Controller
{
    private $flasher = null;

    public function index(){
        $start_date = Carbon::now()->format('d-m-Y');
        $end_date = $start_date;
        return view('pages.zelle-report.index', compact('start_date', 'end_date'));
    }

    public function generateExcel(Request $request, CashRegisterRepository $cash_register_repo){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if ($start_date && $end_date){
            
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $zelle_records = $cash_register_repo
                ->getZelleRecords($new_start_date, $new_finish_date)
                ->groupBy(['cash_register_user', 'date']);

            $file_name = 'Zelle_Records' . ($new_start_date === $new_finish_date 
                ? $start_date 
                : $start_date . 'hasta' . $end_date
                )
                . '.xlsx';

            return Excel::download(new ZelleRecordsExport(compact('zelle_records')), $file_name);
        }
    }
}
