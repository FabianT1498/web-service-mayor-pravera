<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class CashRegisterController extends Controller
{

    public function create()
    {
        
        $date =  Date::now()->format('d-m-Y');

        $cash_registers_id = ['CAJA1', 'CAJA2', 'CAJA13'];

        return view('pages.cash-register.create', compact('date', 'cash_registers_id'));
    }

}
