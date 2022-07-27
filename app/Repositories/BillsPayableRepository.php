<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillsPayableRepository implements BillsPayableRepositoryInterface
{

    public function getBillsPayable($is_dolar, $start_emision_date, $end_emision_date){
     
        return DB
            ::connection('saint_db')
            ->table('SACOMP')
            ->selectRaw("CAST(SACOMP.FechaE AS date) as FechaE, CAST(SACOMP.FechaI AS date) AS FechaPost, SAPROV.Descrip,
                SACOMP.NumeroD, CAST(ROUND(SACOMP.MtoTotal, 2) AS decimal(24, 2)) as MtoTotal, SACOMP_02.USD as esDolar")
            ->join('SACOMP_02', function($query){
                $query->on("SACOMP_02.NumeroD", '=', "SACOMP.NumeroD");
            })
            ->join('SAPROV', function($query){
                $query->on("SAPROV.CodProv", '=', "SACOMP.CodProv");
            })
            ->whereRaw("SACOMP_02.USD = " . $is_dolar . " AND CAST(SACOMP.FechaE as date) BETWEEN " . $start_emision_date . " AND " . $end_emision_date)
            ->orderByRaw("SACOMP.FechaE DESC");
    }
}
