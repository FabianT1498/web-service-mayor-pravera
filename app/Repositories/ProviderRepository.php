<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProviderRepository implements ProviderRepositoryInterface
{
    
    public function getProviders($descrip = ''){
     
        return DB
            ::connection('saint_db')
            ->table('SAPROV')
            ->selectRaw("SAPROV.CodProv AS CodProv, SAPROV.Descrip as Descrip")
            ->whereRaw("SAPROV.Activo = 1 AND " . "SAPROV.Descrip LIKE '%" .
                 $descrip . "%'")
            ->orderByRaw("SAPROV.Descrip asc")
            ->get();
    }
}
