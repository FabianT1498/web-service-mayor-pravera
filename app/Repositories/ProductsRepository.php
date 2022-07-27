<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProductsRepository implements ProductsRepositoryInterface
{
    public function getProductByID($cod_product = '', $conn = ''){
        
        return DB
            ::connection($conn)
            ->table('SAPROD')
            ->selectRaw("SAPROD.CodProd AS CodProd, SAPROD.Descrip as Descrip")
            ->whereRaw("SAPROD.CodProd = '" . $cod_product . "'")
            ->first();
    }

    public function getProducts($descrip = '', $is_active = 1, $instance = null, $there_existance = true, $conn = ''){
     
        return DB
            ::connection($conn)
            ->table('SAPROD')
            ->selectRaw("SAPROD.CodProd AS CodProd, SAPROD.Descrip as Descrip,
                (SELECT Factor from SACONF) as FactorV,
                (SELECT FactorP from SACONF) as Factor, 
                CASE WHEN SAPROD_02.Precio_Manual = 1 
                    THEN CAST(ROUND(SAPROD.CostPro/(SELECT Factor from SACONF), 2) AS decimal(15, 2))
                    ELSE CAST(ROUND(SAPROD.CostPro/(SELECT FactorP from SACONF), 2) AS decimal(15, 2))
                END AS CostoProDiv,
                CASE WHEN SAPROD_02.Precio_Manual = 1 
                    THEN SAPROD_02.Profit1 
                    ELSE CAST(ROUND(((SAPROD.CostPro/(SELECT FactorP from SACONF))/((100 - SAPROD_02.Profit1)/100)), 2) AS decimal(15, 2)) 
                END AS PrecioVDiv,
                CASE WHEN SAPROD_02.Precio_Manual = 1
                    THEN CAST(ROUND(100 - ((((SAPROD.CostPro + (SAPROD.CostPro * (SATAXPRD.Monto / 100)))/(SELECT Factor from SACONF)) * 100)/(SAPROD_02.Profit1)), 2) AS decimal(15, 2))
                    ELSE SAPROD_02.Profit1 
                END AS PorcentajeUtil,
                SAPROD_02.Precio_Manual as EsManual, SATAXPRD.Monto as IVA, CAST(ROUND(SAPROD.Existen, 2) AS decimal(15, 2)) as Existencia, 
                CASE WHEN SAPROD_02.Precio_Manual = 1 
                    THEN CAST(ROUND(SAPROD.Existen * (SAPROD.CostPro/(SELECT Factor from SACONF)), 2) AS decimal(15, 2))
                    ELSE CAST(ROUND(SAPROD.Existen * (SAPROD.CostPro/(SELECT FactorP from SACONF)), 2) AS decimal(15, 2))
                END AS CostoExistenciaDiv")
            ->join('SAPROD_02', function($query){
                $query->on("SAPROD.CodProd", '=', "SAPROD_02.CodProd");
            })
            ->leftJoin('SATAXPRD', function($query){
                $query->on("SAPROD.CodProd", '=', "SATAXPRD.CodProd");
            })
            ->whereRaw("SAPROD.Activo = " . $is_active . (!is_null($instance) ? " AND SAPROD.CodInst = " . $instance  : '') . " AND (SAPROD.Descrip LIKE '%" . $descrip . "%' OR SAPROD.CodProd LIKE '%" .
                 $descrip . "%') AND SAPROD.Existen " . ($there_existance ? ">" : "=") . " 0")
            ->orderByRaw("SAPROD.Descrip asc");
    }

    public function getProductsBySuggestionStatus($status){
        return DB
            ::table('products')
            ->selectRaw("products.cod_prod as cod_prod, products.descrip as descrip, product_suggestions.percent_suggested as percent_suggested, 
                product_suggestions.created_at as created_at")
            ->join(DB::raw("(SELECT MAX(cod_prod) AS cod_prod, MAX(status) as status, MAX(product_suggestions.created_at) as created_at,
                     MAX(product_suggestions.percent_suggested) AS percent_suggested FROM `product_suggestions` GROUP BY cod_prod ORDER BY product_suggestions.created_at DESC) `product_suggestions`"),
                function($join){
                    $join->on('products.cod_prod', '=', 'product_suggestions.cod_prod');
                }
            )
            ->whereRaw("product_suggestions.status = '" . $status . "'");
    }

    public function getTotalCostProducts($conn){
        return DB
            ::connection($conn)
            ->table(DB::raw("(SELECT SAPROD.CodProd as CodProd, SAPROD.Existen * SAPROD.CostPro AS CostoInventario,
                CASE 
                    WHEN SAPROD_02.Precio_Manual = 1 THEN SAPROD.Existen * (SAPROD.CostPro/(SELECT Factor from SACONF))
                    ELSE SAPROD.Existen * (SAPROD.CostPro/(SELECT FactorP from SACONF))
                END AS CostoInventarioDiv FROM SAPROD INNER JOIN SAPROD_02 ON SAPROD.CodProd = SAPROD_02.CodProd) SAPROD")
            )
            ->selectRaw("CAST(ROUND(SUM(SAPROD.CostoInventario), 2) AS decimal(15, 2)) as CostoInventario, CAST(ROUND(SUM(SAPROD.CostoInventarioDiv), 2) AS decimal(15, 2)) AS CostoInventarioDiv")
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

    public function getInstances($conn){
        return DB
            ::connection($conn)
            ->table('SAINSTA')
            ->selectRaw("CodInst, Descrip")
            ->whereRaw("Nivel = 0 AND InsPadre = 0")
            ->get();
    }
}
