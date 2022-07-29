<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillsPayableRepository implements BillsPayableRepositoryInterface
{

    public function getBillsPayable($is_dolar, $start_emision_date, $end_emision_date){
       
        return DB
            ::connection('saint_db')
            ->table('SACOMP')
            ->selectRaw("CAST(SACOMP.FechaE AS date) as FechaE, SACOMP.Descrip, CAST(SACOMP.FechaI AS date) AS FechaPost,
                SACOMP.NumeroD, CAST(ROUND(SACOMP.MtoTotal, 2) AS decimal(24, 2)) as MtoTotal")
            ->whereRaw("CAST(SACOMP.FechaE as date) BETWEEN '" . $start_emision_date . "' AND '" . $end_emision_date . "'")
            ->orderByRaw("SACOMP.FechaE ASC");
    }
}
