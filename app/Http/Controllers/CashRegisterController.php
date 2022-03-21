<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Models\Worker;
use App\Models\CashRegisterData;
use App\Models\CashRegister;
use App\Models\DollarCashRecord;
use App\Models\BsCashRecord;
use App\Models\BsDenominationRecord;
use App\Models\DollarDenominationRecord;
use App\Models\PointSaleBsRecord;
use App\Models\PointSaleDollarRecord;
use App\Models\ZelleRecord;

use App\Http\Requests\StoreCashRegisterRequest;
use App\Http\Requests\EditCashRegisterRequest;
use App\Http\Requests\UpdateCashRegisterRequest;
use App\Http\Requests\PrintSingleCashRegisterRequest;
use App\Http\Requests\PrintIntervalCashRegisterRequest;

class CashRegisterController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }
    
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

    private function getCashRegisterUsersWithoutRecord($date = null){
        $result =  DB::table('cash_register_users')
            ->select([
                'cash_register_users.name as cash_register_id',
            ])
            ->leftJoin('cash_register_data', function($join) use ($date){
                $join
                    ->on('cash_register_data.cash_register_user', '=', 'cash_register_users.name')
                    ->whereDate('cash_register_data.date', '=', $date);
            })
            ->where('cash_register_data.cash_register_user', '=', null)
            ->get();

        return $result
            ->map(function($item, $key) {
                return (object) array("key" => $item->cash_register_id, "value" => $item->cash_register_id);
            });
    }

    public function index(Request $request){
 
        $status = $request->query('status', config('constants.CASH_REGISTER_STATUS.EDITING'));
        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        $query = CashRegisterData::select([
            'cash_register_data.id',
            'users.name as user_name',
            'cash_register_data.cash_register_user',
            'cash_register_data.date',
            'cash_register_data.status',
            'cash_register_data.updated_at',
        ]); 

        $query = $query->join('users', 'cash_register_data.user_id', '=', 'users.id');

        if ($status !== "ALL"){
            $query = $query->where('cash_register_data.status', '=', $status);
        }

        if ($start_date && $end_date){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_finish_date = date('Y-m-d', strtotime($end_date));

            $query = $query->whereBetween('cash_register_data.date', [$new_start_date, $new_finish_date]);
        }

        $status_options = [
            (object) ['key' => 'ALL', 'value' => 'Todos'],
            (object) ['key' => config('constants.CASH_REGISTER_STATUS.EDITING'), 'value' => 'En edición'],
            (object) ['key' => config('constants.CASH_REGISTER_STATUS.COMPLETED'), 'value' => 'Completado'],
        ];

        
        $records = $query->orderBy('date', 'desc')->paginate(5);
        
       $records->appends(['status' => $status, 'start_date' => $start_date, 'end_date' => $end_date]);

        $columns = [
            "Nro",
            "Usuario creador",
            "Nombre de la caja",
            "Fecha del arqueo",
            "Estatus",
            "Última modificacion",
            "Opciones"
        ];

        return view('pages.cash-register.index', compact(
            'columns',
            'records',
            'status',
            'status_options',
            'start_date',
            'end_date',
        ));
    }

    public function create()
    {  
        
        $cash_registers_workers_id_arr = $this->getWorkers();

        $today_date = Carbon::now();
        $cash_registers_id_arr = $this
            ->getCashRegisterUsersWithoutRecord($today_date->format('Y-m-d'));

        $today_date = $today_date->format('d-m-Y');
        
        $data = compact(
            'cash_registers_id_arr',
            'cash_registers_workers_id_arr',
            'today_date'
        );

        return $this->getTableSummaryView('pages.cash-register.create', $data);
    }

    public function store(StoreCashRegisterRequest $request)
    {
        $validated = $request->validated();

        $validated += ["user_id" => Auth::id()];

        if (array_key_exists('new_cash_register_worker', $validated)){
            $worker = new Worker(array('name' => $validated['new_cash_register_worker']));
            $worker->save();
            $validated = array_merge($validated, array('worker_id' => $worker->id));
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

                $credit_data = array_map(function($amount, $bank) use ($cash_register_data){
                    return array(
                        'amount' => $amount,
                        'type' => "CREDIT",
                        'cash_register_data_id' => $cash_register_data->id,
                        'bank_name' => $bank
                    );
                }, $validated['point_sale_bs_credit'], $validated['point_sale_bs_bank']);

                $debit_data = array_map(function($amount, $bank) use ($cash_register_data){
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

            $this->flasher->addSuccess('El arqueo de caja se guardó exitosamente!');
        } else {
            $this->flasher->addError('El arqueo de caja no se pudo guardar');
        }

        return redirect()->route('cash_register.index');
    }

    public function edit(EditCashRegisterRequest $request){

        $cash_register_data = CashRegisterData::where('id', $request->id)->first();

        $dollar_cash_records = $cash_register_data->dollar_cash_records;
        $bs_cash_records = $cash_register_data->bs_cash_records;
        $bs_denomination_records = $cash_register_data->bs_denomination_records;
        $dollar_denomination_records = $cash_register_data->dollar_denomination_records;
        $zelle_records = $cash_register_data->zelle_records;
        
        $point_sale_dollar_record = $cash_register_data
            ->point_sale_dollar_records()
            ->first();

        $cash_registers_workers_id_arr = $this->getWorkers();

        $cash_registers_id_arr = $this
            ->getCashRegisterUsersWithoutRecord($cash_register_data->date);

        $date = $cash_register_data->date->format('d-m-Y');

        $cash_registers_id_arr->prepend((object)[
            'key' => $cash_register_data->cash_register_user,
            'value' => $cash_register_data->cash_register_user]
        );

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
            'date'
        ));
    }

    public function update(UpdateCashRegisterRequest $request){

        $validated = $request->validated();

        $cash_register_data = CashRegisterData::where('id', $request->route('id'))->first();

        if (array_key_exists('new_cash_register_worker', $validated)){
            $worker = new Worker(array('name' => $validated['new_cash_register_worker']));
            $worker->save();
            $validated = array_merge($validated, array('worker_id' => $worker->id));
        }

        $cash_register_data->worker_id = $validated['worker_id'];
        $cash_register_data->cash_register_user = $validated['cash_register_user'];
        $cash_register_data->date = $validated['date'];
      
        if ($cash_register_data->isDirty()){
            $cash_register_data->save();
        }
        
        $cash_register_data_id = $request->route('id');

        // Update Dollar Cash Records
        $dollar_cash_records_coll = $cash_register_data->dollar_cash_records;

        if (!key_exists('dollar_cash_record', $validated) && $dollar_cash_records_coll->count() > 0){
            $dollar_cash_records_coll
                ->each(function($item, $key){
                    $item->delete();
                });
        } else if(key_exists('dollar_cash_record', $validated)){
            $diff = $dollar_cash_records_coll->count() - count($validated['dollar_cash_record']);
            
            if ($diff > 0){
                $to_delete = $dollar_cash_records_coll->splice(0, $diff);
                $to_delete->each(function($item, $key){
                    $item->delete();
                });
            }

            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'totalRecordsColsToUpdate',
                $dollar_cash_records_coll->toArray(),
                $validated['dollar_cash_record'],
            );

            $cash_register_data->dollar_cash_records()->upsert($data, ['id'], ['amount']);
        }

        // Update Bs Cash Records
        $bs_cash_records_coll = $cash_register_data->bs_cash_records;

        if (!key_exists('bs_cash_record', $validated) && $bs_cash_records_coll->count() > 0){
            $bs_cash_records_coll
                ->each(function($item, $key){
                    $item->delete();
                });
        } else if(key_exists('bs_cash_record', $validated)){
            $diff = $bs_cash_records_coll->count() - count($validated['bs_cash_record']);
            
            if ($diff > 0){
                $to_delete = $bs_cash_records_coll->splice(0, $diff);
                $to_delete->each(function($item, $key){
                    $item->delete();
                });
            }

            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'totalRecordsColsToUpdate',
                $bs_cash_records_coll->toArray(),
                $validated['bs_cash_record'],
            );

            $cash_register_data->bs_cash_records()->upsert($data, ['id'], ['amount']);
        }

        // Dollar Denomination Records
        $dollar_denomination_records_coll = $cash_register_data->dollar_denomination_records;
    
        if (!key_exists('dollar_denominations_record', $validated) 
                && $dollar_denomination_records_coll->contains(function($val){ return ($val->quantity > 0); })){
            $cash_register_data->dollar_denomination_records()->update(['quantity' => 0]);
        } else if (key_exists('dollar_denominations_record', $validated)){
            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'denominationRecordsColsToUpdate',
                $dollar_denomination_records_coll->toArray(),
                $validated['dollar_denominations_record'],
            );
            $cash_register_data->dollar_denomination_records()->upsert($data, ['id'], ['quantity']);
        }

        // Bs Denomination Records
        $bs_denomination_records_coll = $cash_register_data->bs_denomination_records;
    
        if (!key_exists('dollar_denominations_record', $validated) 
                && $bs_denomination_records_coll->contains(function($val){ return ($val->quantity > 0); })){
            $cash_register_data->bs_denomination_records()->update(['quantity' => 0]);
        } else if (key_exists('bs_denominations_record', $validated)){
            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'denominationRecordsColsToUpdate',
                $bs_denomination_records_coll->toArray(),
                $validated['bs_denominations_record'],
            );
            $cash_register_data->bs_denomination_records()->upsert($data, ['id'], ['quantity']);
        }

        // Update Point Sale $ Records
        $total_point_sale_dollar = $cash_register_data->point_sale_dollar_records()->first();
        if(!key_exists('total_point_sale_dollar', $validated) && $total_point_sale_dollar){
            $total_point_sale_dollar->delete();
        } else if (key_exists('total_point_sale_dollar', $validated)) {
            $data = [
                'id' => $total_point_sale_dollar ? $total_point_sale_dollar->id : null, 
                'amount' => $validated['total_point_sale_dollar'],
                'cash_register_data_id' => $cash_register_data_id
            ];
            $cash_register_data->point_sale_dollar_records()->upsert($data, ['id'], ['amount']);
        }

        // Update Point Sale Bs Records
        $point_sale_bs_records_coll = $cash_register_data->point_sale_bs_records;

        if (!key_exists('point_sale_bs_bank', $validated) && $point_sale_bs_records_coll->count() > 0){
            $point_sale_bs_records_coll
                ->each(function($item, $key){
                    $item->delete();
                });

        } else if(key_exists('point_sale_bs_bank', $validated)){
            $diff = ($point_sale_bs_records_coll->count() / 2) - count($validated['point_sale_bs_bank']);
            
            if ($diff > 0){
                $to_delete = $point_sale_bs_records_coll->splice(0, $diff * 2);
                $to_delete->each(function($item, $key){
                    $item->delete();
                });
            }
            
            $credit_data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'pointSaleBsCreditColsToUpdate',
                $point_sale_bs_records_coll->slice(0, ($point_sale_bs_records_coll->count() / 2))->toArray(),
                $validated['point_sale_bs_credit'],
                $validated['point_sale_bs_bank'],
            );

            $debit_data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'pointSaleBsDebitColsToUpdate',
                $point_sale_bs_records_coll->slice(($point_sale_bs_records_coll->count() / 2))->toArray(),
                $validated['point_sale_bs_debit'],
                $validated['point_sale_bs_bank'],
            );

            $data = array_merge($credit_data, $debit_data);

            $cash_register_data->point_sale_bs_records()->upsert($data, ['id'], ['amount']);
        }


        // Update Zelle Records
        $zelle_records_coll = $cash_register_data->zelle_records;

        if (!key_exists('zelle_record', $validated) && $zelle_records_coll->count() > 0){
            $zelle_records_coll
                ->each(function($item, $key){
                    $item->delete();
                });
        } else if(key_exists('zelle_record', $validated)){
            $diff = $zelle_records_coll->count() - count($validated['zelle_record']);
            
            if ($diff > 0){
                $to_delete = $zelle_records_coll->splice(0, $diff);
                $to_delete->each(function($item, $key){
                    $item->delete();
                });
            }

            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'totalRecordsColsToUpdate',
                $zelle_records_coll->toArray(),
                $validated['zelle_record'],
            );

            $cash_register_data->zelle_records()->upsert($data, ['id'], ['amount']);
        }

        $this->flasher->addSuccess('El arqueo de caja fue actualizado exitosamente!');
        return redirect()->route('cash_register.index');
    }

    public function finishCashRegister(EditCashRegisterRequest $request){
        $id = $request->id;
        $cash_register_data = CashRegisterData::where('id', $id)->first();
        $cash_register_data->status = config('constants.CASH_REGISTER_STATUS.COMPLETED');
        if ($cash_register_data->save()){

            $result = $this->getTotalsRelatedToRecord($id);

            // toArray method converts eloquent model's properties to primitive data types
            $cash_register = new CashRegister($result->toArray());

            if ($cash_register->save()){
                $this->flasher->addSuccess('El arqueo de caja fue cerrado exitosamente!');
            } else {
                $this->flasher->addError('El arqueo de caja no se pudo cerrar');
            }
        } else {
            $this->flasher->addError('El arqueo de caja no se pudo cerrar');
        }

        return redirect()->route('cash_register.index');
    }

    public function singleRecordPdf(PrintSingleCashRegisterRequest $request){

        $cash_register = CashRegister::where('cash_register_data_id', $request->route('id'))->first();
  
        $totals_from_db_saint = $this->getTotalsFromSaint($cash_register->cash_register_user,
            $cash_register->date);
        
        $totals_saint = [];
        
        // Mapping every cash record entry with its key
        foreach(array_keys(config('constants.CASH_TIPO_FAC')) as $index => $value){
            $record = $totals_from_db_saint['total_cash_records']->slice($index, 1)->first();
            $key = config('constants.CASH_TIPO_FAC.'. $value);

            if (!is_null($record)){
                if ($value === $record->TipoFac){
                    $totals_saint[$key] = $record->total_cash;
                } else {
                    if (!key_exists($key, $totals_saint)){
                        $totals_saint[$key] = 0;
                    }
                    $totals_saint[config('constants.CASH_TIPO_FAC.'. $record->TipoFac)] = $record->total_cash;
                }
            } else if (!key_exists($key, $totals_saint)) {
                $totals_saint[$key] = 0;
            }
        }

        // Mapping every e-payment entry with its key
        foreach(array_keys(config('constants.COD_PAGO')) as $index => $value){
            $record = $totals_from_db_saint['total_e_payment_records']->slice($index, 1)->first();
            $key = config('constants.COD_PAGO.'. $value);

            if (!is_null($record)){
                if ($value === $record->CodPago){
                    $totals_saint[$key] = $record->total;
                } else {
                    if (!key_exists($key, $totals_saint)){
                        $totals_saint[$key] = 0;
                    }
                    $totals_saint[config('constants.COD_PAGO.'. $record->CodPago)] = $record->total;
                }
            } else if (!key_exists($key, $totals_saint)) {
                $totals_saint[$key] = 0;
            }
        }

        $differences = [
            'dollar_cash' => $cash_register->total_dollar_cash - $totals_saint['dollar_cash'],
            'bs_cash' => $cash_register->total_bs_cash - $totals_saint['bs_cash'],
            'point_sale_bs' => ($cash_register->total_point_sale_bs - ($totals_saint['bs_debit'] + $totals_saint['bs_credit'])),
            'point_sale_dollar' => $cash_register->total_point_sale_dollar - $totals_saint['point_sale_dollar'],
            'zelle' => $cash_register->total_zelle - $totals_saint['zelle'],
            'bs_denominations' => $cash_register->total_bs_denominations - $totals_saint['bs_cash'],
            'dollar_denominations' => $cash_register->total_dollar_denominations - $totals_saint['dollar_cash'],
        ];

        $currency_signs = [
            'dollar' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')),
            'bs' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR'))
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('pdf.cash-register.single-record', compact(
            'cash_register',
            'totals_saint',
            'differences',
            'currency_signs'
        ))
            ->setOptions([
                'defaultFont' => 'sans-serif',
            ]);
        return $pdf->download('arqueo-caja_' . $cash_register->cash_register_user . '_' . $cash_register->date . '.pdf');
    }

    public function intervalRecordPdf(PrintIntervalCashRegisterRequest $request){

    }

    private function mergeOldAndNewValues($parent_id, $callback_name, $old_values, ...$new_values){
        $callback = [$this, $callback_name];
        return array_map(function($old_record, ...$new_record) use ($parent_id, $callback){
            return $callback($old_record, $new_record, $parent_id);
        }, $old_values, ...$new_values);
    }

    private function getTotalsFromSaint($cash_register_user, $date){

        // Get the totals of cash registers
        $query =  DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw('
                COALESCE(CAST(ROUND(SUM(SAFACT.Monto), 2) AS decimal(18,2)), 0) as total_cash,
                COALESCE(SAFACT.TipoFac,' . '\'unassigned_TipoFac\'' . ') as TipoFac'
            )
            ->leftJoin("SAIPAVTA", function($join) {
                $join
                    ->on('SAFACT.NumeroD', '=', 'SAIPAVTA.NumeroD');
            })
            ->where('SAFACT.CodUsua', '=', $cash_register_user)
            ->where('SAIPAVTA.NumeroD', '=', null)
            ->whereDate('SAFACT.FechaE', '=', '08-03-2022')
            ->groupBy('SAFACT.TipoFac');
        
        $total_cash_records = $query->get();

        // Get the totals on SAIPAVTA Table
        $query =  DB::connection('saint_db')
            ->table('SAIPAVTA')
            ->selectRaw('
                COALESCE(CAST(ROUND(SUM(SAIPAVTA.Monto), 2) AS decimal(18, 2)), 0) as total,
                COALESCE(SAIPAVTA.CodPago,' . '\'unassigned_CodPago\'' . ') as CodPago,
                COALESCE(SAIPAVTA.TipoFac,' . '\'unassigned_TipoFac\'' . ') as TipoFac'
            )
            ->join("SAFACT", function($join) use ($cash_register_user, $date) {
                $join->on('SAIPAVTA.NumeroD', '=', 'SAFACT.NumeroD')
                    ->where('SAFACT.CodUsua', '=', $cash_register_user)
                    ->whereDate('SAFACT.FechaE', '=', '09-03-2022');
            })
            ->groupBy('SAIPAVTA.CodPago', 'SAIPAVTA.TipoFac');
            
        $total_e_payment_records = $query->get();

        return compact('total_cash_records', 'total_e_payment_records');
    }

    private function getTotalsRelatedToRecord($id){
        $query = CashRegisterData::selectRaw(
            'cash_register_data.id as id,
            cash_register_data.date as date,
            cash_register_data.cash_register_user as cash_register_user,
            workers.name as worker_name,
            users.name as user_name,
            COALESCE(dol_c_join.total, 0) as total_dollar_cash,
            COALESCE(bs_c_join.total, 0) as total_bs_cash,
            COALESCE(ps_bs_join.total, 0) as total_point_sale_bs,
            COALESCE(ps_dol_join.total, 0) as total_point_sale_dollar,
            COALESCE(bs_denomination_join.total, 0) as total_bs_denominations,
            COALESCE(dollar_denomination_join.total, 0) as total_dollar_denominations,
            COALESCE(zelle_join.total, 0) as total_zelle
            '
        )
        ->join('users', 'cash_register_data.user_id', '=', 'users.id')
        ->join('workers', 'cash_register_data.worker_id', '=', 'workers.id')
        ->leftJoin(
            DB::raw("(SELECT SUM(`dollar_cash_records`.`amount`) as `total`, `dollar_cash_records`.`cash_register_data_id` FROM `dollar_cash_records` GROUP BY `dollar_cash_records`.`cash_register_data_id`) `dol_c_join`"),
            function($join) use ($id) {
                $join->on('dol_c_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`bs_cash_records`.`amount`) as `total`, `bs_cash_records`.`cash_register_data_id` FROM `bs_cash_records` GROUP BY `bs_cash_records`.`cash_register_data_id`) `bs_c_join`"),
            function($join) use ($id) {
                $join->on('bs_c_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`point_sale_bs_records`.`amount`) as `total`, `point_sale_bs_records`.`cash_register_data_id` FROM `point_sale_bs_records` GROUP BY `point_sale_bs_records`.`cash_register_data_id`) `ps_bs_join`"),
            function($join) use ($id) {
                $join->on('ps_bs_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("
                (SELECT SUM(`point_sale_dollar_records`.`amount`) as `total`,
                `point_sale_dollar_records`.`cash_register_data_id` 
                FROM `point_sale_dollar_records` 
                GROUP BY 
                    `point_sale_dollar_records`.`cash_register_data_id`
                ) `ps_dol_join`"),
            function($join) use ($id) {
                $join->on('ps_dol_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`bs_denomination_records`.`quantity` * `bs_denomination_records`.`denomination`) as `total`, `bs_denomination_records`.`cash_register_data_id` FROM `bs_denomination_records` GROUP BY `bs_denomination_records`.`cash_register_data_id`) `bs_denomination_join`"),
            function($join) use ($id) {
                $join->on('bs_denomination_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`dollar_denomination_records`.`quantity` * `dollar_denomination_records`.`denomination`) as `total`, `dollar_denomination_records`.`cash_register_data_id` FROM `dollar_denomination_records` GROUP BY `dollar_denomination_records`.`cash_register_data_id`) `dollar_denomination_join`"),
            function($join) use ($id) {
                $join->on('dollar_denomination_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`zelle_records`.`amount`) as `total`, `zelle_records`.`cash_register_data_id` FROM `zelle_records` GROUP BY `zelle_records`.`cash_register_data_id`) `zelle_join`"),
            function($join) use ($id) {
                $join->on('zelle_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->where('cash_register_data.id', '=', $id);

        return $query->first();
    }

    private function totalRecordsColsToUpdate($old_record, $new_record, $parent_id){
        return [
            'id' => $old_record ? $old_record['id'] : null,
            'amount' => $new_record[0],
            'cash_register_data_id' => $parent_id
        ];
    }

    private function denominationRecordsColsToUpdate($old_record, $new_record, $parent_id){
        return [
            'id' => $old_record ? $old_record['id'] : null,
            'quantity' => $new_record[0],
            'denomination' => $old_record ? $old_record['denomination'] : null,
            'cash_register_data_id' => $parent_id
        ];
    }

    private function pointSaleBsDebitColsToUpdate($old_record, $new_record, $parent_id){
        return array_merge(
            $this->pointSaleBsRecordsColsToUpdate($old_record, $new_record, $parent_id),
            ['type' => 'DEBIT']
        );
    }

    private function pointSaleBsCreditColsToUpdate($old_record, $new_record, $parent_id){
        return array_merge(
            $this->pointSaleBsRecordsColsToUpdate($old_record, $new_record, $parent_id),
            ['type' => 'CREDIT']
        );
    }

    private function pointSaleBsRecordsColsToUpdate($old_record, $new_record, $parent_id){
        return [
            'id' => $old_record ? $old_record['id'] : null,
            'amount' => $new_record[0],
            'bank_name' => $new_record[1],
            'cash_register_data_id' => $parent_id
        ];
    }

    public function dollarCashDetails(GetCashRegisterRequest $request)
    {
        $cash_register_data = CashRegisterData::find($request->validated('id'));

        $title = "Facturas de dolares en efectivo";
        $columns = ["id", "Monto"];

        // ( TipoFac = B )En esta categoria esta tanto pagos en dolares en efectivo, zelle y punto internacional
        $bills = DB::connection('saint_db')->table('SAFACT')
            ->select(['NumeroD as id', 'Monto as amount'])
            ->where('CodUsua', '=', $cash_register_data->cash_register_user)
            ->where('TipoFac', '=', 'B')
            ->whereNotIn('')
            ->whereDate('FechaE', '=', Date::now()->format('d-m-Y'))
            ->orderBy('FechaE', 'desc')
            ->paginate(10);
       
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
