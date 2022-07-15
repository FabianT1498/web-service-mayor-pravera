<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProductsRepository implements ProductsRepositoryInterface
{

    public function getProducts($descrip = '', $is_active = 1, $instance = 1652){
        
        return DB
            ::connection('saint_db')
            ->table('SAPROD')
            ->selectRaw("SAPROD.CodProd AS CodProd, SAPROD.Descrip as Descrip, CAST(ROUND(SAPROD.CostPro, 2) AS decimal(15, 2)) as CostoPro,
                CASE WHEN SAPROD_02.Precio_Manual = 1 
                    THEN SAPROD_02.Profit1 
                    ELSE CAST(ROUND(((SAPROD.CostPro/(SELECT FactorP from SACONF))/((100 - SAPROD_02.Profit1)/100)), 2) AS decimal(15, 2)) 
                END AS PrecioV,
                CASE WHEN SAPROD_02.Precio_Manual = 1
                    THEN CAST(ROUND(100 - (((SAPROD.CostPro/(SELECT FactorP from SACONF)) * 100)/SAPROD_02.Profit1), 2) AS decimal(15, 2))
                    ELSE SAPROD_02.Profit1 
                END AS PorcentajeUtil,
                SAPROD_02.Precio_Manual as EsManual, SATAXPRD.Monto as IVA")
            ->join('SAPROD_02', function($query){
                $query->on("SAPROD.CodProd", '=', "SAPROD_02.CodProd");
            })
            ->leftJoin('SATAXPRD', function($query){
                $query->on("SAPROD.CodProd", '=', "SATAXPRD.CodProd");
            })
            // ->whereRaw("SAPROD.Activo = " . $is_active . " AND SAPROD.CodInst = " . $instance . " AND (SAPROD.CodProd LIKE '%" . $cod_product 
            //     . "%' OR SAPROD.Descrip LIKE '%" . $descrip . "%')")
            ->whereRaw("SAPROD.Activo = " . $is_active . " AND SAPROD.CodInst = " . $instance . " AND (SAPROD.Descrip LIKE '%" . $descrip . "%' OR SAPROD.CodProd LIKE '%" . $descrip . "%')")
            ->orderByRaw("SAPROD.Descrip asc");
    }

    public function getInstances(){
        return DB
            ::connection('saint_db')
            ->table('SAINSTA')
            ->selectRaw("CodInst, Descrip")
            ->whereRaw("Nivel = 0 AND InsPadre = 0")
            ->get();
    }
}
