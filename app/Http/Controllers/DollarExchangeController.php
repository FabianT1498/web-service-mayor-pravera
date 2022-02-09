<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;

use App\Models\DollarExchange;
use App\Http\Resources\DollarExchangeResource;

class DollarExchangeController extends Controller
{
    
    public function store(ApiRequest $request)
    {  

        $dollar_exchange = new DollarExchange(['bs_exchange' => 20.20]);
        
        if ($dollar_exchange->save()){

            return new DollarExchangeResource($dollar_exchange);
        }

        return $this->jsonResponse(null, 500);

    }
}
