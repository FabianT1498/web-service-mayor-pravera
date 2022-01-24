<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class CashRegisterController extends Controller
{

    public function createStepOne()
    {
        
        $date =  Date::now()->format('d-m-Y');

        $cash_registers_id = ['CAJA1', 'CAJA2', 'CAJA13'];

        return view('pages.cash-register.create-step-one', compact('date', 'cash_registers_id'));
    }

    public function postCreateStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:products',
            'amount' => 'required|numeric',
            'description' => 'required',
        ]);
  
        if(empty($request->session()->get('product'))){
            $product = new Product();
            $product->fill($validatedData);
            $request->session()->put('product', $product);
        }else{
            $product = $request->session()->get('product');
            $product->fill($validatedData);
            $request->session()->put('product', $product);
        }
  
        return redirect()->route('products.create.step.two');
    }

}
