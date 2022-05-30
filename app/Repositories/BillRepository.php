<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillRepository implements BillRepositoryInterface
{

    public function getValesAndVueltos($start_date, $end_date){
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

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("SAFACT.CodUsua as CodUsua, CAST(SAFACT.FechaE as date) as FechaE, SAFACT.NumeroD as NumeroD, CASE WHEN EGIVALES.FactUso = '' THEN 'Efectivo' ELSE EGIVALES.FactUso END as FactUso, CAST(ROUND(EGIVALES.MontoDiv, 2) AS decimal(10, 2)) as MontoDiv,
                FactorHist.MaxFactor as Factor, CAST(ROUND(EGIVALES.MontoDiv * Factor, 2) AS decimal(10, 2)) as MontoBs")  
            ->join('EGIVALES', 'EGIVALES.FactEmi', '=', 'SAFACT.NumeroD')
            ->joinSub($factors, 'FactorHist', function($query){
                $query->on(DB::raw("CAST(SAFACT.FechaE AS date)"), '=', "FactorHist.FechaE");
            })
            ->whereRaw("EGIVALES.Estado = 'V' AND SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date) asc, SAFACT.NumeroD asc, EGIVALES.FactUso asc")
            ->get();
            
    }

    public function getTotalValesAndVueltosByUser($start_date, $end_date, $user = null){
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

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE AS date) as FechaE, CASE WHEN EGIVALES.FactUso = '' THEN 'Efectivo' ELSE EGIVALES.FactUso END as FactUso, MAX(EGIVALES.Estado) as Estado,  CAST(ROUND(SUM(EGIVALES.MontoDiv * SAFACT.Signo), 2) AS decimal(10, 2)) AS MontoDiv, 
                CAST(ROUND(SUM(EGIVALES.MontoDiv * FactorHist.MaxFactor * SAFACT.Signo), 2) AS decimal(10, 2)) as MontoBs")  
            ->join('EGIVALES', 'EGIVALES.FactEmi', '=', 'SAFACT.NumeroD')
            ->joinSub($factors, 'FactorHist', function($query){
                $query->on(DB::raw("CAST(SAFACT.FechaE AS date)"), '=', "FactorHist.FechaE");
            })
            ->whereRaw("EGIVALES.Estado = 'V' AND SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua " . $user_params . " AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE AS date), EGIVALES.FactUso")
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE AS date) asc")
            ->get();
    }
}