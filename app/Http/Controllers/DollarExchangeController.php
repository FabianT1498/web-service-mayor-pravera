<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDollarExchangeRequest;
use App\Models\DollarExchange;
use App\Http\Resources\DollarExchangeResource;


class DollarExchangeController extends Controller
{
   
    public function store(StoreDollarExchangeRequest $request)
    {  
        $dollar_exchange = new DollarExchange($request->validated());

        if ($dollar_exchange->save()){
            return new DollarExchangeResource($dollar_exchange);
        }

        return $this->jsonResponse([], 500);

    }
}
