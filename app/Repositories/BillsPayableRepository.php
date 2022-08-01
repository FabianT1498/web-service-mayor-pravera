<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillsPayableRepository implements BillsPayableRepositoryInterface
{

    // Metodo para obtener las facturas por pagar
    public function getBillsPayable($is_dolar, $before_emission_date, $bill_type){

        $is_bill_NE = config('constants.BILL_PAYABLE_TYPE.' . $bill_type) === config('constants.BILL_PAYABLE_TYPE.NE');
        
        $sacomp_sub_join =  $is_bill_NE
            ?
                "(SELECT SACOMP.NumeroD, SACOMP.CodProv FROM SACOMP 
                LEFT JOIN (SELECT NumeroD, CodProv from SACOMP WHERE SACOMP.TipoCom = 'H' AND CAST(SACOMP.FechaE AS date)  <= '". $before_emission_date . "') SACOMP_FAC
                    ON (SACOMP.NumeroD = SACOMP_FAC.NumeroD AND SACOMP.CodProv = SACOMP_FAC.CodProv)
                WHERE SACOMP.TipoCom = 'J' AND CAST(SACOMP.FechaE AS date)  <= '" . $before_emission_date . "' AND SACOMP_FAC.NumeroD IS NULL AND SACOMP_FAC.CodProv IS NULL) SACOMP_SUB"
            : 
                "(SELECT NumeroD, CodProv from SACOMP WHERE SACOMP.TipoCom = 'H') SACOMP_SUB";
       
        return DB
            ::connection('saint_db')
            ->table('SAACXP')
            ->selectRaw("SAACXP.NumeroD, SAACXP.CodProv, SAACXP.Descrip, SAACXP.TipoCxP, CAST(SAACXP.Monto AS decimal(10, 2)) AS MontoTotal,
                CAST(SAACXP.Saldo AS decimal(10, 2)) AS MontoPagar, SACOMP_02.USD AS esDolar, CAST(SAACXP.FechaI AS date) as FechaPosteo, CAST(SAACXP.FechaE AS date) AS FechaE")
            ->join(DB::raw($sacomp_sub_join),
                function($join){
                    $join->on('SACOMP_SUB.NumeroD', '=', 'SAACXP.NumeroD')
                        ->on('SACOMP_SUB.CodProv', '=', 'SAACXP.CodProv');
                }
            )
            ->join('SACOMP_02', function($join){
                $join->on('SACOMP_SUB.NumeroD', '=', 'SACOMP_02.NumeroD')
                    ->on('SACOMP_SUB.CodProv', '=', 'SACOMP_02.CodProv');
                }
            )
            ->whereRaw("SAACXP.TipoCxP = 10 AND SAACXP.Saldo > 0 AND CAST(SAACXP.FechaE AS date) <= '" . $before_emission_date . "' AND SACOMP_02.USD = " . $is_dolar)
            ->orderByRaw("SAACXP.FechaE DESC");
    }
}
