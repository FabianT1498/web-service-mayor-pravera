<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use App\Models\CashRegister;

use App\Http\Requests\StoreCashRegisterStepOneRequest;

class CashRegisterController extends Controller
{
   
    private function getTableSummaryView($stepViewName, $objects){
        return view($stepViewName, $objects);
    }
    
    private function getSessionCashRegisterData($request){
        return $request->session()->get('cash_register');
    }

    private function getBillsAmounts($bills){
        return array_map(function($bill){
            return $bill->amount;
        }, $bills);
    }

    public static function getSumAmount($amounts) {
        return array_reduce($amounts, function($carry, $item){
            return ($carry + $item);
        });
    }

    public function createStepOne()
    {
        
        $date =  Date::now()->format('d-m-Y');

        $cash_registers_id = ['CAJA1', 'CAJA2', 'CAJA13'];

        $data = compact('date', 'cash_registers_id');

        return $this->getTableSummaryView('pages.cash-register.create-step-one', $data);
    }

    public function postCreateStepOne(StoreCashRegisterStepOneRequest $request)
    {
        $validated = $request->validated();

        if(empty($request->session()->get('cash_register'))){
            $cash_register = new CashRegister();
            $cash_register->fill($validated);
            $request->session()->put('cash_register', $cash_register);
        }else{
            $cash_register = $request->session()->get('cash_register');
            $cash_register->fill($validated);
            $request->session()->put('cash_register', $cash_register);
        }

        return redirect()->route('cash_register_step_three.create');
    }

    public function createStepTwo(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $columns = ["id", "Monto"];
        $bills = [
            (object) array("id" => "12243", "amount" => 2500.34),
            (object) array("id" => "232", "amount" => 25800.34),
            (object) array("id" => "12234343", "amount" => 2980.34),
            (object) array("id" => "5454", "amount" => 23212.34),
        ];

        $amounts = $this->getBillsAmounts($bills);
        $sum_amount = $this::getSumAmount($amounts);
        

        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amounts');

        return $this->getTableSummaryView('pages.cash-register.create-step-one', $data);
    }

    public function createStepThree(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);
        
        /** Here should be handle the queries to the database */
        $data = compact('cash_register');
        return $this->getTableSummaryView('pages.cash-register.create-step-one', $data);
    }


    public function createStepFour(Request $request)
    {
        $cash_register = $request->session()->get('cash_register');
        

        return view('pages.cash-register.create-step-four', compact('cash_register'));
    }

    public function createStepFive(Request $request)
    {
        $cash_register = $request->session()->get('cash_register');
        

        return view('pages.cash-register.create-step-five', compact('cash_register'));
    }

    public function createStepSix(Request $request)
    {
        $cash_register = $request->session()->get('cash_register');
        

        return view('pages.cash-register.create-step-six', compact('cash_register'));
    }

    public function store(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);
        $cash_register->save();

        $request->session()->forget('cash_register');

        return redirect()->route('dashboard');
    }
}
