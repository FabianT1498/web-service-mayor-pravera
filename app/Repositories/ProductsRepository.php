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
                SAPROD_02.Precio_Manual as EsManual, SATAXPRD.Monto as IVA, CAST(ROUND(SAPROD.Existen, 2) AS decimal(15, 2)) as Existencia, 
                CAST(ROUND(SAPROD.Existen * SAPROD.CostPro, 2) AS decimal(15, 2)) as CostoExistencia")
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

    public function getProductsBySuggestionStatus($status, $cod_products = ''){
        return DB
            ::table('products')
            ->selectRaw("products.cod_prod")
            ->join(DB::raw("(SELECT MAX(cod_prod) AS cod_prod, MAX(status) as status, MAX(product_suggestions.created_at) as created_at FROM `product_suggestions` GROUP BY cod_prod) `product_suggestions`"),
                function($join){
                    $join->on('products.cod_prod', '=', 'product_suggestions.cod_prod');
                }
            )
            ->whereRaw("products.cod_prod IN (" . $cod_products . ") AND product_suggestions.status = '" . $status . "'");
    }

    public function getTotalCostProducts(){
        return DB
            ::connection('saint_db')
            ->table('SAPROD')
            ->selectRaw("
                CAST(ROUND(SUM(SAPROD.Existen * SAPROD.CostPro), 2) AS decimal(15, 2)) as CostoInventario")
            ->first();
    }

    public function getSuggestions($cod_product){
        
        return DB
            ::table('products')
            ->selectRaw("products.cod_prod, product_suggestions.id, DATE_FORMAT(product_suggestions.created_at,'%d-%m-%Y') as created_at,
                product_suggestions.percent_suggested, product_suggestions.user_name, product_suggestions.status")
            ->join('product_suggestions', 'products.cod_prod', '=', 'product_suggestions.cod_prod')
            ->whereRaw("products.cod_prod = " . $cod_product)
            ->orderByRaw("product_suggestions.created_at DESC")
            ->get();
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
