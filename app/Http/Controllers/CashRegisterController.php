<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

use App\Models\CashRegister;
use App\Models\Worker;
use App\Models\CashRegisterData;
use App\Models\DollarCashRecord;
use App\Models\BsCashRecord;
use App\Models\BsDenominationRecord;
use App\Models\DollarDenominationRecord;
use App\Models\PointSaleBsRecord;
use App\Models\PointSaleDollarRecord;
use App\Models\ZelleRecord;


use App\Http\Requests\StoreCashRegisterRequest;
use App\Http\Requests\GetCashRegisterRequest;

use App\Models\DollarExchange;
use Illuminate\Support\Facades\Auth;

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

    private function getWorkers(){
        $cash_register_workers = DB::table('workers')
            ->select()
            ->get();

        return $cash_register_workers->map(function($item, $key) {
            return (object) array("key" => $item->id, "value" => $item->name);
        });
    }

    private function getCashRegisterUsers(){
        $cash_registers_id = DB::connection('saint_db')->table('SSUSRS')
        ->select('CodUsua as cash_register_id')
        ->where("CodUsua", "LIKE", "CAJA%")
        ->where("CodUsua", "=", "DELIVERY", 'or')
        ->get();

        return $cash_registers_id->map(function($item, $key) {
            return (object) array("key" => $item->cash_register_id, "value" => $item->cash_register_id);
        });
    }

    public function create()
    {  
        $date =  Date::now()->format('d-m-Y');

        $cash_registers_workers_id_arr = $this->getWorkers();
        $cash_registers_id_arr = $this->getCashRegisterUsers();

        $data = compact('date', 'cash_registers_id_arr', 'cash_registers_workers_id_arr');

        return $this->getTableSummaryView('pages.cash-register.create', $data);
    }

    public function store(StoreCashRegisterRequest $request)
    {
        $validated = $request->validated();

        $date =  Date::now()->format('d-m-Y');

        $validated += ["date" => $date];
        $validated += ["user_id" => Auth::id()];

        if (array_key_exists('new_cash_register_worker', $validated)){
            $worker = new Worker(array('name' => $validated['new_cash_register_worker']));
            $worker->save();
            array_merge($validated, array('worker_id' => $worker->id));
        }

        $cash_register_data = new CashRegisterData($validated);
        
        if ($cash_register_data->save()){
            if (array_key_exists('dollar_cash_record', $validated)){
                $data = array_map(function($value) use ($cash_register_data){
                    return array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                }, $validated['dollar_cash_record']);
                DollarCashRecord::insert($data);
            }
    
            if (array_key_exists('bs_cash_record', $validated)){
                $data = array_map(function($value) use ($cash_register_data){
                    return array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                }, $validated['dollar_cash_record']);
                BsCashRecord::insert($data);
            }
    
            if (array_key_exists('dollar_denominations_record', $validated)){
                $data = array_map(function($quantity, $denomination) use ($cash_register_data){
                    return array(
                        'quantity' => $quantity,
                        'denomination' => floatval($denomination . 'El'),
                        'cash_register_data_id' => $cash_register_data->id
                    );
                }, $validated['dollar_denominations_record'], array_keys($validated['dollar_denominations_record']));
                DollarDenominationRecord::insert($data);
            }
    
            if (array_key_exists('bs_denominations_record', $validated)){
                $data = array_map(function($quantity, $denomination) use ($cash_register_data){
                    return array(
                        'quantity' => $quantity,
                        'denomination' => floatval($denomination . 'El'),
                        'cash_register_data_id' => $cash_register_data->id
                    );
                }, $validated['bs_denominations_record'], array_keys($validated['bs_denominations_record']));
                BsDenominationRecord::insert($data);
            }
            
            if (array_key_exists('point_sale_bs_bank', $validated)){

                $credit_data = array_map(function($amount, $bank) use ($cash_register_data, $date){
                    return array(
                        'amount' => $amount,
                        'type' => "CREDIT",
                        'cash_register_data_id' => $cash_register_data->id,
                        'bank_name' => $bank
                    );
                }, $validated['point_sale_bs_credit'], $validated['point_sale_bs_bank']);

                $debit_data = array_map(function($amount, $bank) use ($cash_register_data, $date){
                    return array(
                        'amount' => $amount,
                        'type' => "DEBIT",
                        'cash_register_data_id' => $cash_register_data->id,
                        'bank_name' => $bank
                    );
                }, $validated['point_sale_bs_debit'], $validated['point_sale_bs_bank']);

                $data = array_merge($credit_data, $debit_data);

                PointSaleBsRecord::insert($data);
            }

            if (array_key_exists('total_point_sale_dollar', $validated)){
                $data = [
                    'amount' => $validated['total_point_sale_dollar'],
                    'cash_register_data_id' => $cash_register_data->id
                ];
                PointSaleDollarRecord::insert($data);
            }
    
            if (array_key_exists('zelle_record', $validated)){
                $data = array_map(function($value) use ($cash_register_data){
                    return array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                }, $validated['zelle_record']);
                ZelleRecord::insert($data);
            }

            toastr()->success('El registro fue guardado con exito');
            return redirect()->route('cash_register.edit', [$cash_register_data->id]);
        }

        toastr()->error('No se pudo guardar el registro');
        return redirect()->route('dashboard');
    }

    public function edit($id, GetCashRegisterRequest $request){

        $cash_register_data = CashRegisterData::where('id', $id)->first();
        
        $dollar_cash_records = $cash_register_data->dollar_cash_records;
        $bs_cash_records = $cash_register_data->bs_cash_records;
        $bs_denomination_records = $cash_register_data->bs_denomination_records;
        $dollar_denomination_records = $cash_register_data->dollar_denomination_records;
        $zelle_records = $cash_register_data->zelle_records;
        
        $point_sale_dollar_record = $cash_register_data
            ->point_sale_dollar_records()
            ->first();

        $cash_registers_workers_id_arr = $this->getWorkers();
        $cash_registers_id_arr = $this->getCashRegisterUsers();

        $banks = DB::connection('caja_mayorista')
            ->table('banks')
            ->select('name')
            ->whereNotIn('banks.name', function($query) use ($cash_register_data){
                $query
                    ->select('point_sale_bs_records.bank_name')
                    ->from('point_sale_bs_records')
                    ->where('point_sale_bs_records.cash_register_data_id', '=', $cash_register_data->id)
                    ->groupBy('point_sale_bs_records.bank_name');
            })     
            ->get();

        $point_sale_bs_records = $cash_register_data
            ->point_sale_bs_records()
            ->orderBy('bank_name')
            ->get();
 
        // Banks array is just an array of literal strings
        $point_sale_bs_banks = $point_sale_bs_records
            ->unique('bank_name')
            ->values()
            ->map(function($record){
                return $record->bank_name;
            });

        // point_sale_bs_records is an array whose credit and debit entries contains Eloquent Models
        $point_sale_bs_records_arr = $point_sale_bs_records->reduce(function ($arr, $item) {
            if ($item->type === "CREDIT"){
                array_push($arr['credit'], $item);
            } else {
                array_push($arr['debit'], $item);
            }
            return $arr;
        }, ['credit' => [], 'debit' => []]);

        $point_sale_bs_records_arr = array_merge($point_sale_bs_records_arr, ['bank' => $point_sale_bs_banks]);

        // Total amounts
        $total_dollar_cash = $dollar_cash_records->reduce(function($carry, $el){
            return $carry + $el->amount;
        }, 0);

        $total_bs_cash = $bs_cash_records->reduce(function($carry, $el){
            return $carry + $el->amount;
        }, 0);

        $total_point_sale_bs = $point_sale_bs_records->reduce(function($carry, $el){
            return $carry + $el->amount;
        }, 0);

        $total_dollar_denominations = $dollar_denomination_records->reduce(function($carry, $el){
            return $carry + ($el->quantity * $el->denomination);
        }, 0);

        $total_bs_denominations = $bs_denomination_records->reduce(function($carry, $el){
            return $carry + ($el->quantity * $el->denomination);
        }, 0);

        $total_zelle = $zelle_records->reduce(function($carry, $el){
            return $carry + $el->amount;
        }, 0);

        return view('pages.cash-register.edit', compact(
            'cash_register_data',
            'total_dollar_cash',
            'total_bs_cash',
            'total_point_sale_bs',
            'total_dollar_denominations',
            'total_bs_denominations',
            'total_zelle',
            'dollar_cash_records',
            'bs_cash_records',
            'point_sale_dollar_record',
            'point_sale_bs_records_arr',
            'banks',
            'bs_denomination_records',
            'dollar_denomination_records',
            'zelle_records',
            'cash_registers_id_arr',
            'cash_registers_workers_id_arr',
        ));
    }

    // public function dollarCashDetails(GetCashRegisterRequest $request)
    // {
    //     $cash_register_data = CashRegisterData::find($request->validated('id'));

    //     $title = "Facturas de dolares en efectivo";
    //     $columns = ["id", "Monto"];

    //     // ( TipoFac = B )En esta categoria esta tanto pagos en dolares en efectivo, zelle y punto internacional
    //     $bills = DB::connection('saint_db')->table('SAFACT')
    //         ->select(['NumeroD as id', 'Monto as amount'])
    //         ->where('CodUsua', '=', $cash_register_data->cash_register_user)
    //         ->where('TipoFac', '=', 'B')
    //         ->whereNotIn('')
    //         ->whereDate('FechaE', '=', Date::now()->format('d-m-Y'))
    //         ->orderBy('FechaE', 'desc')
    //         ->paginate(10);
       
    //     $amounts = $this->getBillsAmounts($bills);
       
    //     $sum_amount = $this::getSumAmount($amounts);

    //     $this->format_amount($bills, $amounts);
        
    //     /** Here should be handle the queries to the database */
    //     $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

    //     return $this->getTableSummaryView('pages.cash-register.create-step-two', $data);
    // }

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
            ->paginate(10);

            
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
        ->paginate(10);

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
        
        $bills = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '01');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc')
            ->paginate(10);
    
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
        
        $bills = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '02');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc')
            ->paginate(10);
        
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
        
        $bills = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '03');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc')
            ->paginate(10);
      
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

        $bills = DB::connection('saint_db')->table('SAFACT')
            ->select(['SAFACT.NumeroD as id', 'SAFACT.Monto as amount'])
            ->join('SAIPAVTA', function($join){
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD')
                    ->where('SAIPAVTA.CodPago', '=', '05');
            })
            ->whereDate('SAFACT.FechaE', '=', Date::now()->format('Y-m-d'))
            ->where('SAFACT.CodUsua', '=', $cash_register->cash_register_id)
            ->orderBy('SAFACT.FechaE', 'desc')
            ->paginate(10);

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
        ->paginate(10);

        $amounts = $this->getBillsAmounts($bills);
    
        $sum_amount = $this::getSumAmount($amounts);

        $this->format_amount($bills, $amounts);
        
        /** Here should be handle the queries to the database */
        $data = compact('cash_register', 'columns', 'bills', 'sum_amount', 'title');

        return $this->getTableSummaryView('pages.cash-register.create-step-nine', $data);
    }
}
