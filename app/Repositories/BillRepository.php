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

        $egivales_sub_query = "(SELECT FactEmi, MontoDiv FROM EGIVALES WHERE EGIVALES.Estado = 'V' AND EGIVALES.FactUso = '') AS EGIVALES";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodEsta) as CodEsta, CAST(SAFACT.FechaE as date) as FechaE, MAX(SAFACT.NumeroD) as NumeroD, 
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivEfect,
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * SAFACT.FactorV * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsEfect,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoD, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivPM,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoBs, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsPM,
                MAX(SAFACT.FactorV) as Factor
            ")
            ->leftJoin(DB::raw($egivales_sub_query), 'EGIVALES.FactEmi', '=', 'SAFACT.NumeroD')
            ->leftJoin('TBL_REG_PagoM', 'TBL_REG_PagoM.NumeroD', '=', 'SAFACT.NumeroD')
            ->whereRaw("SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodEsta IN ('CAJA-1', 'CAJA2', 'CAJA-3', 'CAJA4', 'CAJA5', 'CAJA6' , 'DELIVERYPB')
                AND (EGIVALES.FactEmi IS NOT NULL OR TBL_REG_PagoM.NumeroD IS NOT NULL) AND " . $interval_query, $queryParams)
            ->orderByRaw("SAFACT.CodEsta asc, CAST(SAFACT.FechaE as date) asc, SAFACT.NumeroD asc")
            ->groupByRaw("SAFACT.CodEsta,  CAST(SAFACT.FechaE as date), SAFACT.NumeroD")
            ->get();
    }

    public function getVueltosByUser($start_date, $end_date, $user = null){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $user_params = $user
          ? " = '" . $user . "'"
          : "IN ('CAJA-1', 'CAJA2', 'CAJA-3', 'CAJA4', 'CAJA5', 'CAJA6' , 'DELIVERYPB')";

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        $egivales_sub_query = "(SELECT FactEmi, MontoDiv FROM EGIVALES WHERE EGIVALES.Estado = 'V' AND EGIVALES.FactUso = '') AS EGIVALES";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodEsta) as CodEsta, CAST(SAFACT.FechaE as date) as FechaE, 
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivEfect,
                CAST(ROUND(SUM(COALESCE(EGIVALES.MontoDiv, 0) * SAFACT.FactorV * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsEfect,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoD, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoDivPM,
                CAST(ROUND(SUM(COALESCE(TBL_REG_PagoM.MontoBs, 0) * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBsPM
            ")
            ->leftJoin(DB::raw($egivales_sub_query), 'EGIVALES.FactEmi', '=', 'SAFACT.NumeroD')
            ->leftJoin('TBL_REG_PagoM', 'TBL_REG_PagoM.NumeroD', '=', 'SAFACT.NumeroD')
            ->whereRaw("SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodEsta " . $user_params . " AND " .
                "(EGIVALES.FactEmi IS NOT NULL OR TBL_REG_PagoM.NumeroD IS NOT NULL) AND " . $interval_query, $queryParams)
            ->orderByRaw("SAFACT.CodEsta asc, CAST(SAFACT.FechaE as date) asc")
            ->groupByRaw("SAFACT.CodEsta,  CAST(SAFACT.FechaE as date)")
            ->get();
    }
}