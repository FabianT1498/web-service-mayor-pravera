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
use App\Models\PagoMovilRecord;
use App\Models\BsDenominationRecord;
use App\Models\DollarDenominationRecord;
use App\Models\PointSaleBsRecord;
use App\Models\PointSaleDollarRecord;
use App\Models\ZelleRecord;
use App\Models\DollarExchange;

use App\Http\Requests\StoreCashRegisterRequest;
use App\Http\Requests\EditCashRegisterRequest;
use App\Http\Requests\UpdateCashRegisterRequest;
use App\Http\Requests\PrintSingleCashRegisterRequest;
use App\Http\Requests\PrintIntervalCashRegisterRequest;

use App\Repositories\CashRegisterRepository;

class CashRegisterController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    // private function contains_step_route($sub_route){
    //     return str_contains($sub_route, 'create-step');
    // }


    private function getWorkers(){
        $cash_register_workers = DB::table('workers')
            ->select()
            ->get();

        return $cash_register_workers->map(function($item, $key) {
            return (object) array("key" => $item->id, "value" => $item->name);
        });
    }

    public function getCashRegisterUsersWithoutRecord($date = null){
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
            'cash_register_data.user_id as user_name',
            'cash_register_data.cash_register_user',
            'cash_register_data.date',
            'cash_register_data.status',
            'cash_register_data.updated_at',
        ]);

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

        
        // $records->appends(['status' => $status, 'start_date' => $start_date, 'end_date' => $end_date]);

        // return print_r($records);

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

        if ($cash_registers_id_arr->count() === 0){
            $this->flasher->addInfo('Ya se han registrado arqueos de caja para todas las cajas el dia de hoy,
                por favor seleccione otra fecha');
        }

        $today_date = $today_date->format('d-m-Y');

        $data = compact(
            'cash_registers_id_arr',
            'cash_registers_workers_id_arr',
            'today_date'
        );

        return view('pages.cash-register.create', $data);
    }

    public function store(StoreCashRegisterRequest $request)
    {
        $validated = $request->validated();

        $validated += ["user_id" => Auth::user()->CodUsua];

        if (array_key_exists('new_cash_register_worker', $validated)){
            $worker = new Worker(array('name' => $validated['new_cash_register_worker']));
            $worker->save();
            $validated = array_merge($validated, array('worker_id' => $worker->id));
        }

        $cash_register_data = new CashRegisterData($validated);

        if ($cash_register_data->save()){
            if (array_key_exists('dollar_cash_record', $validated)){
                $data = array_reduce($validated['dollar_cash_record'], function($acc, $value) use ($cash_register_data){
                    if ($value > 0){
                        return array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                    }

                    return $acc;
                }, []);
                DollarCashRecord::insert($data);
            }

            if (array_key_exists('bs_cash_record', $validated)){
                $data = array_reduce($validated['bs_cash_record'], function($acc, $value) use ($cash_register_data){
                    if ($value > 0){
                        return array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                    }

                    return $acc;
                }, []);
                BsCashRecord::insert($data);
            }

            if (array_key_exists('pago_movil_record', $validated)){
                $data = array_reduce($validated['pago_movil_record'], function($acc, $value) use ($cash_register_data){
                    if ($value > 0){
                        return array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                    }

                    return $acc;
                }, []);
                PagoMovilRecord::insert($data);
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

            if (array_key_exists('point_sale_bs', $validated)){

                $credit_data = array_map(function($amount, $bank) use ($cash_register_data){
                    return array(
                        'amount' => $amount,
                        'type' => "CREDIT",
                        'cash_register_data_id' => $cash_register_data->id,
                        'bank_name' => $bank
                    );
                }, $validated['point_sale_bs']['credit'], $validated['point_sale_bs']['bank']);


                $debit_data = array_map(function($amount, $bank) use ($cash_register_data){
                    return array(
                        'amount' => $amount,
                        'type' => "DEBIT",
                        'cash_register_data_id' => $cash_register_data->id,
                        'bank_name' => $bank
                    );
                }, $validated['point_sale_bs']['debit'], $validated['point_sale_bs']['bank']);

                $data = array_merge($credit_data, $debit_data);

                PointSaleBsRecord::insert($data);
            }

            if (array_key_exists('total_point_sale_dollar', $validated) 
                && $validated['total_point_sale_dollar'] > 0){
                $data = [
                    'amount' => $validated['total_point_sale_dollar'],
                    'cash_register_data_id' => $cash_register_data->id
                ];
                PointSaleDollarRecord::insert($data);
            }

            if (array_key_exists('zelle_record', $validated)){
                $data = array_reduce($validated['zelle_record'], function($acc, $value) use ($cash_register_data){
                    if ($value > 0){
                        return array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                    }

                    return $acc;
                }, []);
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

        // Obtener el ultimo valor registrado para la tasa en esta fecha o una fecha anterior al arqueo
        $old_dollar_exchange = DollarExchange::whereDate('created_at', '<=', $cash_register_data->date)
            ->orderBy('created_at', 'desc')
            ->first();

        // Si no hay ninguna tasa registrada en la misma fecha o previo al arqueo, entonces recuperar la ultima tasa
        if (!$old_dollar_exchange){
            $old_dollar_exchange = DollarExchange::orderBy('created_at', 'desc')
            ->first();
        }

        $dollar_cash_records = $cash_register_data->dollar_cash_records;
        $bs_cash_records = $cash_register_data->bs_cash_records;
        $pago_movil_bs_records = $cash_register_data->pago_movil_bs_records;
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

        $total_pago_movil_bs = $pago_movil_bs_records->reduce(function($carry, $el){
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
            'total_pago_movil_bs',
            'total_point_sale_bs',
            'total_dollar_denominations',
            'total_bs_denominations',
            'total_zelle',
            'dollar_cash_records',
            'bs_cash_records',
            'pago_movil_bs_records',
            'point_sale_dollar_record',
            'point_sale_bs_records_arr',
            'banks',
            'bs_denomination_records',
            'dollar_denomination_records',
            'zelle_records',
            'cash_registers_id_arr',
            'cash_registers_workers_id_arr',
            'date',
            'old_dollar_exchange'
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

        // Update Pago movilr ecords
        $pago_movil_bs_records_coll = $cash_register_data->pago_movil_bs_records;

        if (!key_exists('pago_movil_record', $validated) && $pago_movil_bs_records_coll->count() > 0){
            $pago_movil_bs_records_coll
                ->each(function($item, $key){
                    $item->delete();
                });
        } else if(key_exists('pago_movil_record', $validated)){
            $diff = $pago_movil_bs_records_coll->count() - count($validated['pago_movil_record']);

            if ($diff > 0){
                $to_delete = $pago_movil_bs_records_coll->splice(0, $diff);
                $to_delete->each(function($item, $key){
                    $item->delete();
                });
            }

            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'totalRecordsColsToUpdate',
                $pago_movil_bs_records_coll->toArray(),
                $validated['pago_movil_record'],
            );

            $cash_register_data->dollar_cash_records()->upsert($data, ['id'], ['amount']);
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

            if ($validated['total_point_sale_dollar'] === 0){
                if ($total_point_sale_dollar){
                    $total_point_sale_dollar->delete();
                }
            } else {
                $data = [
                    'id' => $total_point_sale_dollar ? $total_point_sale_dollar->id : null,
                    'amount' => $validated['total_point_sale_dollar'],
                    'cash_register_data_id' => $cash_register_data_id
                ];
                $cash_register_data->point_sale_dollar_records()->upsert($data, ['id'], ['amount']);
            }
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

    public function finishCashRegister(CashRegisterRepository $cash_register_repo, EditCashRegisterRequest $request){
        $id = $request->id;
        $cash_register_data = CashRegisterData::where('id', $id)->first();
        $cash_register_data->status = config('constants.CASH_REGISTER_STATUS.COMPLETED');
        
        if ($cash_register_data->save()){

            $result = $cash_register_repo->getTotals($id);

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

    // Método para completar los métodos de pagos que no han tenido ingresos
    // por cada caja en cada fecha.
    private function mapEPaymentMethods($data, $payment_methods){

        $totals_saint = [];

        // Iterating every cash register user
        foreach($data->keys() as $cod_usua){
            $totals_saint[$cod_usua] = [];
            // Iterating over every date of cash register user
            foreach ($data[$cod_usua]->keys() as $date){
                $totals_saint[$cod_usua][$date] = [];
                // Mapping every total with its key
                foreach($payment_methods->keys() as $index => $value){
                    $record = $data[$cod_usua][$date]->slice($index, 1)->first();
            
                    if (!is_null($record)){
                        if ($value === $record->CodPago){
                            $totals_saint[$cod_usua][$date][$value]['bs'] = $record->totalBs;
                            $totals_saint[$cod_usua][$date][$value]['dollar'] = $record->totalDollar;
                        } else {
                            if (!key_exists($value, $totals_saint[$cod_usua][$date])){
                                $totals_saint[$cod_usua][$date][$value]['bs'] = 0.00;
                                $totals_saint[$cod_usua][$date][$value]['dollar'] = 0.00;
                            }
                            $totals_saint[$cod_usua][$date][$record->CodPago]['bs'] = $record->totalBs;
                            $totals_saint[$cod_usua][$date][$record->CodPago]['dollar'] = $record->totalDollar;
                        }
                    } else if (!key_exists($value, $totals_saint[$cod_usua][$date])) {
                        $totals_saint[$cod_usua][$date][$value]['bs'] = 0.00;
                        $totals_saint[$cod_usua][$date][$value]['dollar'] = 0.00;
                    }
                }
            }
        }

        return $totals_saint;
    }

    private function getPaymentMethods(){
        return DB::table('payment_methods')->orderByRaw("CodPago asc")->get()->groupBy(['CodPago']);
    }

    public function singleRecordPdf(CashRegisterRepository $cash_register_repo, PrintSingleCashRegisterRequest $request){
        
        $id = $request->route('id');

        $cash_register = CashRegister::where('cash_register_data_id', $id)->first();

        $totals = $cash_register_repo->getTotals($id);

        $totals_from_safact = $cash_register_repo->getTotalsFromSafact($cash_register->date,
            $cash_register->date, $cash_register->cash_register_user)->first();

        $payment_methods = $this->getPaymentMethods();

        $totals_e_payment =  $cash_register_repo
            ->getTotalsEPaymentMethods($cash_register->date, $cash_register->date,
                $cash_register->cash_register_user)
            ->groupBy(['CodUsua', 'FechaE']);
        
        $totals_e_payment  = $this
            ->mapEPaymentMethods($totals_e_payment, $payment_methods);
        
        $user = $cash_register->cash_register_user;
        $date = date('Y-m-d', strtotime($cash_register->date));
 
        $differences = [
            'dollar_cash' => $totals->total_dollar_cash - $totals_from_safact->dolares,
            'bs_cash' => $totals->total_bs_cash - $totals_from_safact->bolivares,
            'pago_movil_bs' => $totals->total_pago_movil_bs - $totals_e_payment[$user][$date]['05']['bs'],
            'point_sale_bs' => ($totals->total_point_sale_bs - ($totals_e_payment[$user][$date]['01']['bs'] 
                + $totals_e_payment[$user][$date]['02']['bs'])),
            'point_sale_dollar' => $totals->total_point_sale_dollar - $totals_e_payment[$user][$date]['08']['dollar'],
            'zelle' => $totals->total_zelle - $totals_e_payment[$user][$date]['07']['dollar'],
            'bs_denominations' => $totals->total_bs_denominations - $totals_from_safact->bolivares,
            'dollar_denominations' => $totals->total_dollar_denominations - $totals_from_safact->dolares,
        ];

        $cash_register_data = CashRegisterData::find($id);
       
        $denominations_dollar = $cash_register_data->dollar_denomination_records;
        $denominations_bolivar = $cash_register_data->bs_denomination_records;

        $total_denominations_dollar = $denominations_dollar->reduce(function ($acc, $item) {
            return $acc + ($item->quantity * $item->denomination);
        }, 0);

        $total_denominations_bolivar = $denominations_bolivar->reduce(function ($acc, $item) {
            return $acc + ($item->quantity * $item->denomination);
        }, 0);

        $currency_signs = [
            'dollar' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')),
            'bs' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR'))
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('pdf.cash-register.single-record', compact(
            'cash_register',
            'totals_e_payment',
            'totals_from_safact',
            'denominations_dollar',
            'denominations_bolivar',
            'total_denominations_dollar',
            'total_denominations_bolivar',
            'differences',
            'currency_signs',
            'user',
            'date'
        ))
            ->setOptions([
                'defaultFont' => 'sans-serif',
            ]);
        return $pdf->stream('arqueo-caja_' . $cash_register->cash_register_user . '_' . $cash_register->date . '.pdf');
    }

    public function intervalRecordPdf(CashRegisterRepository $cash_register_repo, PrintIntervalCashRegisterRequest $request){
        
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        // Get Saint data
        $totals_from_safact = $cash_register_repo
            ->getTotalsFromSafact($start_date, $end_date)
            ->groupBy(['CodUsua', 'FechaE']);

        $payment_methods = $this->getPaymentMethods();

        $totals_e_payment =  $cash_register_repo
            ->getTotalsEPaymentMethods($start_date, $end_date)
            ->groupBy(['CodUsua', 'FechaE']);
        
        $totals_e_payment  = $this
            ->mapEPaymentMethods($totals_e_payment, $payment_methods);

        // Get database data
        $cash_registers = CashRegister::whereBetween('date', [$start_date, $end_date])
            ->get()
            ->groupBy(['cash_register_user', 'date']);

        $differences = $this->calculateDiffToSaint($totals_from_safact, $totals_e_payment, $cash_registers);
    
        $dollar_denominations = DB::table('cash_register_data')
            ->join('dollar_denomination_records', 'cash_register_data.id', '=', 'dollar_denomination_records.cash_register_data_id')
            ->whereRaw("cash_register_data.date BETWEEN ? AND ?", [$start_date, $end_date])
            ->where('cash_register_data.status', config('constants.CASH_REGISTER_STATUS.COMPLETED'))
            ->orderByRaw("cash_register_data.cash_register_user asc, cash_register_data.date asc, dollar_denomination_records.denomination asc")
            ->get()
            ->groupBy(['cash_register_user', 'date']);

        $bs_denominations = DB::table('cash_register_data')
        ->join('bs_denomination_records', 'cash_register_data.id', '=', 'bs_denomination_records.cash_register_data_id')
        ->whereRaw("cash_register_data.date BETWEEN ? AND ?", [$start_date, $end_date])
        ->where('cash_register_data.status', config('constants.CASH_REGISTER_STATUS.COMPLETED'))
        ->orderByRaw("cash_register_data.cash_register_user asc, cash_register_data.date asc, bs_denomination_records.denomination asc")
        ->get()
        ->groupBy(['cash_register_user', 'date']);

        $currency_signs = [
            'dollar' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')),
            'bs' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR'))
        ];

        $totals_bs_denominations = $this->sumSubTotalDenomination($bs_denominations);
        $total_dollar_denominations = $this->sumSubTotalDenomination($dollar_denominations);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $saint_totals = $this->joinSaintMoneyEntranceCollections($totals_from_safact,
            $totals_e_payment);


        $pdf = App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('pdf.cash-register.interval-record', compact(
            'saint_totals',
            'currency_signs',
            'cash_registers',
            'differences',
            'dollar_denominations',
            'bs_denominations',
            'totals_bs_denominations',
            'total_dollar_denominations',
            'start_date',
            'end_date'
        ))
            ->setOptions([
                'defaultFont' => 'sans-serif',
            ]);


        return $pdf->stream('arqueos-de-caja_' . $start_date . '_' . $end_date . '.pdf');
    }

    private function joinSaintMoneyEntranceCollections($totals_from_safact, $totals_e_payment){
        $saint_totals = [];
        foreach($totals_from_safact as $key_user => $dates){
            $saint_totals[$key_user] = [];
            
            if (array_key_exists($key_user, $totals_e_payment)){
                foreach ($dates as $key_date => $date){
                    $saint_totals[$key_user][$key_date] = [];
                    
                    if (array_key_exists($key_date, $totals_e_payment[$key_user])){
                        $totals_e_payment[$key_user][$key_date]['dolares'] = $date[0]->dolares;
                        $totals_e_payment[$key_user][$key_date]['bolivares'] = $date[0]->bolivares;
                        $saint_totals[$key_user][$key_date] = $totals_e_payment[$key_user][$key_date];
                    } else {
                        $saint_totals[$key_user][$key_date]['dolares'] = $date[0]->dolares;
                        $saint_totals[$key_user][$key_date]['bolivares'] = $date[0]->bolivares;     
                    }
                }
            } else {
                foreach ($dates as $key_date => $date){
                    $saint_totals[$key_user][$key_date]['dolares'] = $date[0]->dolares;
                    $saint_totals[$key_user][$key_date]['bolivares'] = $date[0]->bolivares; 
                }
            }
        }

        return $saint_totals;
    }

    private function sumSubTotalDenomination($denomination_records){

        $totals_denominations = [];
        foreach($denomination_records as $key_user => $dates){
            $totals_denominations[$key_user] = [];

            foreach ($dates as $key_date => $date){
            
                $totals_denominations[$key_user][$key_date] = $date->reduce(function($acc, $item){
                    return $acc + ($item->quantity * $item->denomination);
                }, 0);
            }
        }

        return $totals_denominations;
    }

    private function calculateDiffToSaint($totals_from_safact, $totals_e_payment, $cash_registers){
        $differences = [];

        $totals_from_safact->each(function($dates, $key_user) use ($cash_registers, &$differences){
            $differences[$key_user] = [];
            if ($cash_registers->has($key_user)){
                $dates->each(function($date, $key_date) use (&$differences, $key_user, $cash_registers){
                    if ($cash_registers[$key_user]->has($key_date)){
                        $differences[$key_user][$key_date] = [];
                        $differences[$key_user][$key_date]['dollar_cash'] = $cash_registers[$key_user][$key_date]->first()->total_dollar_cash - $date[0]->dolares;
                        $differences[$key_user][$key_date]['bs_cash'] = $cash_registers[$key_user][$key_date]->first()->total_bs_cash - $date[0]->bolivares;
                    } else {
                        $differences[$key_user][$key_date] = [];
                        $differences[$key_user][$key_date]['dollar_cash'] = $date[0]->dolares * -1;
                        $differences[$key_user][$key_date]['bs_cash'] = $date[0]->bolivares * -1;
                    }
                });
            } else {
                $dates->each(function($date, $key_date) use (&$differences, $key_user){
                    $differences[$key_user][$key_date] = [];
                    $differences[$key_user][$key_date]['dollar_cash'] = $date[0]->dolares * -1;
                    $differences[$key_user][$key_date]['bs_cash'] = $date[0]->bolivares * -1;
                });
            }
        });

        foreach($totals_e_payment as $key_user => $dates){
            
            if (!array_key_exists($key_user, $differences)){
                $differences[$key_user]  = [];
            }

            if ($cash_registers->has($key_user)){
                
                foreach($dates as $key_date => $date){
                    if ($cash_registers[$key_user]->has($key_date)){
                            $differences[$key_user][$key_date]['point_sale_bs'] = $cash_registers[$key_user][$key_date]->first()->total_point_sale_bs - ($date['01']['bs'] + $date['02']['bs']);
                            $differences[$key_user][$key_date]['point_sale_dollar'] = $cash_registers[$key_user][$key_date]->first()->total_point_sale_dollar - $date['08']['dollar'];
                            $differences[$key_user][$key_date]['pago_movil_bs'] = $cash_registers[$key_user][$key_date]->first()->total_pago_movil_bs - $date['05']['bs'];
                            $differences[$key_user][$key_date]['zelle'] = $cash_registers[$key_user][$key_date]->first()->total_zelle -  $date['07']['dollar'];
                    } else {
                        if (!array_key_exists($key_date, $differences[$key_user])){
                            $differences[$key_user][$key_date] = [];
                        }
                        $differences[$key_user][$key_date]['point_sale_bs'] = ($date['01']['bs'] + $date['02']['bs']) * -1;
                        $differences[$key_user][$key_date]['point_sale_dollar'] = $date['08']['dollar'] * -1;
                        $differences[$key_user][$key_date]['pago_movil_bs'] = $date['05']['bs'] * -1;
                        $differences[$key_user][$key_date]['zelle'] = $date['07']['dollar'] * -1;
                    }
                };
            } else {
                foreach($dates as $key_date => $date){
                    if (!array_key_exists($key_date, $differences[$key_user])){
                        $differences[$key_user][$key_date] = [];
                    }

                    $differences[$key_user][$key_date]['point_sale_bs'] = ($date['01']['bs'] + $date['02']['bs']) * -1;
                    $differences[$key_user][$key_date]['point_sale_dollar'] = $date['08']['dollar'] * -1;
                    $differences[$key_user][$key_date]['pago_movil_bs'] = $date['05']['bs'] * -1;
                    $differences[$key_user][$key_date]['zelle'] = $date['07']['dollar'] * -1;
                }
            }
        }

        return $differences;
    }

    private function mergeOldAndNewValues($parent_id, $callback_name, $old_values, ...$new_values){
        $callback = [$this, $callback_name];
        return array_map(function($old_record, ...$new_record) use ($parent_id, $callback){
            return $callback($old_record, $new_record, $parent_id);
        }, $old_values, ...$new_values);
    }

    public function getTotalsFromSaint(CashRegisterRepository $cash_register_repo, $user, $start_date, $end_date){

        $totals_from_safact = $cash_register_repo->getTotalsFromSafact($start_date, $end_date, $user);

        $totals_e_payments = $cash_register_repo->getTotalsEPaymentMethods($start_date, $end_date, $user);

        return $this->jsonResponse(['data' => [
          'totals_from_safact' => $totals_from_safact,
          'totals_e_payments' =>  $totals_e_payments
        ]], 200);
    }

    public function getTotals(CashRegisterRepository $cash_register_repo, $id){
        $totals = $cash_register_repo->getTotals($id);

        return $this->jsonResponse(['data' => $totals], 200);
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
}
