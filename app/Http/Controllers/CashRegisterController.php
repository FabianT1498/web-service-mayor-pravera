<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

use App\Models\CashRegister;
use App\Models\Worker;
use App\Http\Requests\StoreCashRegisterStepOneRequest;


class CashRegisterController extends Controller
{
    
    private function getTableSummaryView($stepViewName, $objects){
        return view($stepViewName, $objects);
    }
    
    private function getSessionCashRegisterData($request){
        return $request->session()->get('cash_register', null);
    }

    private function getBillsAmounts($bills){
        return $bills->map(function($bill){
            return floatval($bill->amount . 'El');
        });
    }

    private function substring_sub_route_prev_url($route){
        return str_replace(url('/') . $route . '/', '', url()->previous());
    }

    private function contains_step_route($sub_route){
        return str_contains($sub_route, 'create-step');
    }

    public static function getSumAmount($amounts) {
        return $amounts->reduce(function($carry, $item){
            return ($carry + $item);
        });
    }

    private function format_amount(& $bills, $amounts_float){
        $bills->each(function($bill, $key) use ($amounts_float){
            $bill->amount = number_format($amounts_float[$key], 2, '.', ',');
        });
    }

    public function createStepOne()
    {  
        $date =  Date::now()->format('d-m-Y');

        $cash_registers_id = DB::connection('saint_db')->table('SSUSRS')
            ->select('CodUsua as cash_register_id')
            ->where("CodUsua", "LIKE", "CAJA%")
            ->where("CodUsua", "=", "DELIVERY", 'or')
            ->get();

        $cash_register_workers = DB::table('workers')
            ->select()
            ->get();

        $cash_registers_id_arr = $cash_registers_id->map(function($item, $key) {
            return (object) array("key" => $item->cash_register_id, "value" => $item->cash_register_id);
        });

        $cash_registers_workers_id_arr = $cash_register_workers->map(function($item, $key) {
            return (object) array("key" => $item->id, "value" => $item->name);
        });

        $data = compact('date', 'cash_registers_id_arr', 'cash_registers_workers_id_arr');

        return $this->getTableSummaryView('pages.cash-register.create-step-one', $data);
    }

    public function postCreateStepOne(StoreCashRegisterStepOneRequest $request)
    {
        $validated = $request->validated();
        $date =  Date::now()->format('d-m-Y');

        $validated += ["date" => $date];

        if (array_key_exists('new_cash_register_worker', $validated)){
            $worker = new Worker(array(
                'name' => $validated['new_cash_register_worker']
            ));
            
            $worker->save();

            unset($validated['new_cash_register_worker']);
            array_merge($validated, array('cash_register_worker' => $worker->id));
        }

        if(empty($request->session()->get('cash_register'))){
            $cash_register = new CashRegister();
            $cash_register->fill($validated);
            $request->session()->put('cash_register', $cash_register);
        }else{
            $cash_register = $request->session()->get('cash_register');
            $cash_register->fill($validated);
            $request->session()->put('cash_register', $cash_register);
        }

        return redirect()->route('cash_register_step_two.create');
    }

    public function createStepTwo(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);
        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }
        
        $title = "Facturas de dolares en efectivo";
        $columns = ["id", "Monto"];

        // ( TipoFac = B )En esta categoria esta tanto pagos en dolares en efectivo, zelle y punto internacional
        $bills = DB::connection('saint_db')->table('SAFACT')
            ->select(['NumeroD as id', 'Monto as amount'])
            ->where('CodUsua', '=', $cash_register->cash_register_id)
            ->where('TipoFac', '=', 'B')
            ->whereDate('FechaE', '=', Date::now()->format('d-m-Y'))
            ->orderBy('FechaE', 'desc')
            ->get();

        $amounts = $this->getBillsAmounts($bills);
       
        $sum_amount = $this::getSumAmount($amounts);

        $this->format_amount($bills, $amounts);
        
        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-two', $data);
    }

    public function createStepThree(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);
        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }

        $title = "Facturas de Bs en efectivo";
        $columns = ["id", "Monto"];
    
        // Consulta que extrae la diferencia entre SAFACT y SAIPAVTA
        // Para entraer las facturas de pagos en efectivo (Bs.)
        $bills = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->whereNotIn('SAFACT.NumeroD', function($query){
                $query
                    ->select('SAIPAVTA.NumeroD')
                    ->from('SAIPAVTA');
            })
            ->where('SAFACT.TipoFac', '=', 'A')
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->orderBy('SAFACT.FechaE', 'desc')
            ->get();
            
        $amounts = $this->getBillsAmounts($bills);
       
        $sum_amount = $this::getSumAmount($amounts);

        $this->format_amount($bills, $amounts);
        
        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');
       
        return $this->getTableSummaryView('pages.cash-register.create-step-three', $data);
    }

    public function createStepFour(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }

        $title = "Facturas de pagos en Zelle";
        $columns = ["id", "Monto"];
        
        $bills = DB::connection('saint_db')->table('SAFACT')
        ->select(['NumeroD as id', 'Monto as amount'])
        ->where('CodUsua', '=', $cash_register->cash_register_id)
        ->where('TipoFac', '=', 'B')
        ->whereDate('FechaE', '=', Date::now()->format('d-m-Y'))
        ->orderBy('FechaE', 'desc')
        ->get();

        $amounts = $this->getBillsAmounts($bills);
    
        $sum_amount = $this::getSumAmount($amounts);

        $this->format_amount($bills, $amounts);

        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-four', $data);
    }

    public function createStepFive(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }

        $title = "Facturas de pagos en Punto de venta Bs (Debito)";
        $columns = ["id", "Monto"];
        
        $bills_builder = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '01');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc');
        
        $bills = $bills_builder->get();

        $amounts = $this->getBillsAmounts($bills);
        $sum_amount = $this::getSumAmount($amounts);
        

        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-five', $data);
    }

    public function createStepSix(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }
        
        $title = "Facturas de pagos en Punto de venta Bs (Credito)";
        $columns = ["id", "Monto"];
        
        $bills_builder = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '02');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc');
        
        $bills = $bills_builder->get();

        $amounts = $this->getBillsAmounts($bills);
        $sum_amount = $this::getSumAmount($amounts);
        

        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-six', $data);
    }

    public function createStepSeven(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }

        $title = "Facturas de pagos en Punto de venta Bs (Todo Ticket)";
        $columns = ["id", "Monto"];
        
        $bills_builder = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '03');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc');
        
        $bills = $bills_builder->get();

        $amounts = $this->getBillsAmounts($bills);
        $sum_amount = $this::getSumAmount($amounts);
        

        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-seven', $data);
    }

    public function createStepEight(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }

        $title = "Facturas de pagos en transferencia y pago movil";
        $columns = ["id", "Monto"];

        $bills_builder = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '05');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc');

        $bills = $bills_builder->get();

        $amounts = $this->getBillsAmounts($bills);
    
        $sum_amount = $this::getSumAmount($amounts);

        $this->format_amount($bills, $amounts);
        
        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-eight', $data);
    }

    public function createStepNine(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $sub_route = $this->substring_sub_route_prev_url('cash-register');

        if (!$this->contains_step_route($sub_route) || is_null($cash_register)){
            return redirect()->route('cash_register_step_one.create');
        }

        $title = "Facturas de pagos en Punto de venta internacional ($)";
        $columns = ["id", "Monto"];

        $bills = DB::connection('saint_db')->table('SAFACT')
        ->select(['NumeroD as id', 'Monto as amount'])
        ->where('CodUsua', '=', $cash_register->cash_register_id)
        ->where('TipoFac', '=', 'B')
        ->whereDate('FechaE', '=', Date::now()->format('d-m-Y'))
        ->orderBy('FechaE', 'desc')
        ->get();

        $amounts = $this->getBillsAmounts($bills);
    
        $sum_amount = $this::getSumAmount($amounts);

        $this->format_amount($bills, $amounts);
        

        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-nine', $data);
    }

    public function store(Request $request)
    {
        $cash_register = $this->getSessionCashRegisterData($request);

        $cash_register->save();

        $request->session()->forget('cash_register');

        return redirect()->route('dashboard');
    }
}
