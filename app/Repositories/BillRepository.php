<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillRepository implements BillRepositoryInterface
{

    public function getVueltos($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        $factors = DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROUND(MAX(SAFACT.Factor), 2) as MaxFactor, CAST(SAFACT.FechaE as date) as FechaE")
            ->whereRaw($interval_query, $queryParams)
            ->groupByRaw("CAST(SAFACT.FechaE as date)");

        $egivales_sub_query = "(SELECT FactEmi, MontoDiv FROM EGIVALES WHERE EGIVALES.Estado = 'V' AND EGIVALES.FactUso = '') AS EGIVALES";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE as date) as FechaE, MAX(SAFACT.NumeroD) as NumeroD, 
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivEfect,
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * FactorHist.MaxFactor * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsEfect,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoD, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivPM,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoBs, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsPM,
                MAX(FactorHist.MaxFactor) as Factor
            ")
            ->joinSub($factors, 'FactorHist', function($query){
                $query->on(DB::raw("CAST(SAFACT.FechaE AS date)"), '=', "FactorHist.FechaE");
            })
            ->leftJoin(DB::raw($egivales_sub_query), 'EGIVALES.FactEmi', '=', 'SAFACT.NumeroD')
            ->leftJoin('TBL_REG_PagoM', 'TBL_REG_PagoM.NumeroD', '=', 'SAFACT.NumeroD')
            ->whereRaw("SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND (EGIVALES.FactEmi IS NOT NULL OR TBL_REG_PagoM.NumeroD IS NOT NULL) AND " . $interval_query, $queryParams)
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date) asc, SAFACT.NumeroD asc")
            ->groupByRaw("SAFACT.CodUsua,  CAST(SAFACT.FechaE as date), SAFACT.NumeroD")
            ->get();
    }

    public function getVueltosByUser($start_date, $end_date, $user = null){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $user_params = $user
          ? " = '" . $user . "'"
          : "IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY')";

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        $factors = DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROUND(MAX(SAFACT.Factor), 2) as MaxFactor, CAST(SAFACT.FechaE as date) as FechaE")
            ->whereRaw($interval_query, $queryParams)
            ->groupByRaw("CAST(SAFACT.FechaE as date)");

        $egivales_sub_query = "(SELECT FactEmi, MontoDiv FROM EGIVALES WHERE EGIVALES.Estado = 'V' AND EGIVALES.FactUso = '') AS EGIVALES";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE as date) as FechaE, 
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivEfect,
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * FactorHist.MaxFactor * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsEfect,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoD, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivPM,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoBs, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsPM
            ")
            ->joinSub($factors, 'FactorHist', function($query){
                $query->on(DB::raw("CAST(SAFACT.FechaE AS date)"), '=', "FactorHist.FechaE");
            })
            ->leftJoin(DB::raw($egivales_sub_query), 'EGIVALES.FactEmi', '=', 'SAFACT.NumeroD')
            ->leftJoin('TBL_REG_PagoM', 'TBL_REG_PagoM.NumeroD', '=', 'SAFACT.NumeroD')
            ->whereRaw("SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua " . $user_params . " AND " .
                "(EGIVALES.FactEmi IS NOT NULL OR TBL_REG_PagoM.NumeroD IS NOT NULL) AND " . $interval_query, $queryParams)
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date) asc")
            ->groupByRaw("SAFACT.CodUsua,  CAST(SAFACT.FechaE as date)")
            ->get();
    }
}