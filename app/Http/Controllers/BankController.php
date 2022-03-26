<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class BankController extends Controller
{
    //
    public function getAll(Request $request)
    {  
        
        $banks = DB::connection('caja_mayorista')
            ->table('banks')
            ->select('name')
            ->get();

        $banks = $banks->map(function ($item, $key){
            return $item->name;
        });

        return $this->jsonResponse(['data' => $banks, 'status' => 201], 201);

    }
}
