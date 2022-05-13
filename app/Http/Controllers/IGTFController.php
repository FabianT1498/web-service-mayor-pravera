<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Excel;

use App\Exports\IGTFExport;


class IGTFController extends Controller
{
    private $flasher = null;

    public function index(){
        $start_date = Carbon::now()->format('d-m-Y');
        $end_date = $start_date;
        return view('pages.igtf-tax.index', compact('start_date', 'end_date'));
    }

    private function getIGTFRecords($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROW_NUMBER() OVER(ORDER BY SAFACT.FechaE, SAFACT.CodClie asc) AS Row, FORMAT(CAST(SAFACT.FechaE as date), 'dd-MM-yyyy') as FechaE, SAFACT.NumeroD as NumeroD,
                SAFACT.CodClie as CedulaClie,CAST((((SAFACT.MtoExtra * 100) / 3) * SAFACT.Signo) AS decimal(10, 2)) as baseImponible,
                CAST((SAFACT.MtoExtra * SAFACT.Signo) AS decimal(10, 2)) as IGTF")  
            ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.MtoExtra > 0 AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->get();
    }

    private function getTotalIGTFRecords($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("CAST(SUM((((SAFACT.MtoExtra * 100) / 3) * SAFACT.Signo)) AS decimal(10, 2)) as baseImponible,
            CAST(SUM(SAFACT.MtoExtra * SAFACT.Signo) AS decimal(10, 2)) as IGTF")  
            ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.MtoExtra > 0 AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->get()
            ->first();
    }

    private function getIGTFData($start_date, $end_date){
        $igtf_records = $this->getIGTFRecords($start_date, $end_date);
        
        $total = [];
        $total = $total + array('Row' => '');
        $total = $total + array('FechaE' => '');
        $total = $total + array('NumeroD' => '');
        $total = $total + array('CedulaClie' => 'Total');
        $total = $total + ((array) $this->getTotalIGTFRecords($start_date, $end_date));
        $total = (object) $total;
        
        $igtf_records->push($total);
        
        return $igtf_records;
    }

    public function generateExcel(Request $request){
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        if (!$start_date && !$end_date){
            $start_date = Carbon::now()->format('Y-m-d');
            $end_date = Carbon::now()->format('Y-m-d');
        }
        
        $new_start_date = date('Y-m-d', strtotime($start_date));
        $new_finish_date = date('Y-m-d', strtotime($end_date));

        $data = $this->getIGTFData($new_start_date, $new_finish_date);

        $name = 'IGTF_Tax_' . ($new_start_date === $new_finish_date 
            ? $start_date 
            : $start_date . 'hasta' . $end_date
            )
            . '.xlsx';

        return Excel::download(new IGTFExport($data), $name);
    }
}
