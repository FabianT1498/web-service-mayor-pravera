<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ZBillRepository implements ZBillRepositoryInterface
{

    public function getTotalsFromSafact($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE as date) as FechaE,  COALESCE(MAX(SAFACT.NumeroP), '—') as NumeroP,
                COALESCE(MAX(SAFACT.NumeroZ), '—') as NumeroZ, CAST(COALESCE(SUM(SAFACT.TExento * SAFACT.Signo), 0.00) AS decimal(10,2)) as ventaTotalExenta,
                CAST(SUM(SAFACT.MtoTotal * SAFACT.Signo) AS decimal(10,2)) as ventaTotalIVA")  
            ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date), SAFACT.NumeroP, SAFACT.NumeroZ")
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date) asc")
            ->get()
            ->groupBy(['CodUsua', 'FechaE', 'NumeroP', 'NumeroZ']);
    }

    public function getAmountBills($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE as date) as FechaE,  COALESCE(MAX(SAFACT.NumeroP), '—') as NumeroP,
                COALESCE(MAX(SAFACT.NumeroZ), '—') as NumeroZ, COUNT(SAFACT.NumeroF) AS nroFacturas, COALESCE(MAX(SAFACT.NumeroF), '—')  AS ultimoNroFactura")  
            ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.TipoFac = 'A' AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date), SAFACT.NumeroP, SAFACT.NumeroZ")
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date) asc")
            ->get()
            ->groupBy(['CodUsua', 'FechaE', 'NumeroP', 'NumeroZ']);
    }

    public function getTotalLicores($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/       
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAITEMFAC.FechaE as date) = ?"
            : "CAST(SAITEMFAC.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAITEMFAC')
            ->selectRaw("MAX(SAFACT.CodUsua) as CodUsua, CAST(SAFACT.FechaE as date) as FechaE, COALESCE(MAX(SAFACT.NumeroP), '—') as NumeroP,
                COALESCE(MAX(SAFACT.NumeroZ), '—') as NumeroZ, CAST(COALESCE(SUM(SAITEMFAC.Cantidad * SAITEMFAC.Precio * SAFACT.Signo), 0.00) AS decimal(10,2)) as ventaLicoresBS")
            ->join('SAPROD', function($query){
                $query
                    ->on("SAITEMFAC.CodItem", '=', "SAPROD.CodProd")
                    ->whereRaw("SAPROD.CodInst IN (1664, 1653)");
            })
            ->join('SAFACT', function($query){
                $query
                    ->on("SAFACT.NumeroD", '=', "SAITEMFAC.NumeroD")
                    ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                    'CAJA6' , 'CAJA7', 'DELIVERY')");
            })
            ->whereRaw("SAITEMFAC.EsExento = 1 AND " . $interval_query, $queryParams)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date), SAFACT.NumeroP, SAFACT.NumeroZ")
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date) asc")
            ->get()
            ->groupBy(['CodUsua', 'FechaE', 'NumeroP', 'NumeroZ']);

    }

    public function getBaseImponibleByTax($start_date, $end_date){
        /* Consulta para obtener los totales de las facturas*/
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];
        
        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
        ::connection('saint_db')
        ->table('SAFACT')
        ->selectRaw("MAX(SAFACT.CodUsua) AS CodUsua, CAST(SAFACT.FechaE as date) as FechaE, COALESCE(MAX(SAFACT.NumeroP), '—') as NumeroP, COALESCE(MAX(SAFACT.NumeroZ), '—') as NumeroZ,
            MAX(SATAXVTA.CodTaxs) as CodTaxs, COALESCE(SUM(SATAXVTA.TGravable * SAFACT.Signo), 0.00) as TGravable")
        ->join('SATAXVTA', function($query){
            $query
                ->on("SATAXVTA.NumeroD", '=', "SAFACT.NumeroD");
        })
        ->whereRaw("SAFACT.EsNF = 0 AND SAFACT.TipoFac IN ('A', 'B') AND SATAXVTA.CodTaxs IN ('IVA', 'IVA8') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
            'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
        ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date), SAFACT.NumeroP, SAFACT.NumeroZ, SATAXVTA.CodTaxs")
        ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date), SATAXVTA.CodTaxs desc")
        ->get()
        ->groupBy(['CodUsua', 'FechaE', 'NumeroP', 'NumeroZ']);
    }

    public function getZNumbersByPrinter($start_date, $end_date){

        /* Consulta para obtener los totales de las facturas*/      
        $queryParams = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date) 
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("CAST(SAFACT.FechaE as date) as FechaE, CASE WHEN MAX(SAFACT.TipoFac) = 'A' THEN 'TKZ' ELSE 'NKZ' END AS TipoFac,
                COALESCE(SAFACT.NumeroP, '—') AS NumeroP, COALESCE(MAX(SAFACT.NumeroZ), '—') AS NumeroZ,
                COALESCE(MIN(SAFACT.NumeroF), '—') AS MinNumeroF, COALESCE(MAX(SAFACT.NumeroF), '—') AS MaxNumeroF")  
            ->whereRaw("SAFACT.NumeroP IS NOT NULL AND SAFACT.EsNF = 0 AND SAFACT.TipoFac IN ('A', 'B') AND SAFACT.CodUsua IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
            'CAJA6' , 'CAJA7', 'DELIVERY') AND " . $interval_query, $queryParams)
            ->groupByRaw("CAST(SAFACT.FechaE AS date), SAFACT.TipoFac, SAFACT.NumeroP, SAFACT.NumeroZ")
            ->orderByRaw("CAST(SAFACT.FechaE AS date) ASC, SAFACT.NumeroP ASC")
            ->get();
    }
}