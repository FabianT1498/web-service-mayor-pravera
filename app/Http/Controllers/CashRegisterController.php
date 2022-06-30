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
use App\Models\PagoMovilRecord;
use App\Models\BsDenominationRecord;
use App\Models\DollarDenominationRecord;
use App\Models\PointSaleBsRecord;
use App\Models\PointSaleDollarRecord;
use App\Models\ZelleRecord;
use App\Models\Note;

use App\Models\DollarExchange;

use App\Http\Requests\StoreCashRegisterRequest;
use App\Http\Requests\EditCashRegisterRequest;
use App\Http\Requests\UpdateCashRegisterRequest;
use App\Http\Requests\PrintSingleCashRegisterRequest;
use App\Http\Requests\PrintIntervalCashRegisterRequest;

use App\Repositories\CashRegisterRepository;
use App\Repositories\BillRepository;

class CashRegisterController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

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
        $page = $request->query('page', '');

        $query = CashRegisterData::selectRaw(
            'cash_register_data.id,
            MAX(cash_register_data.user_id) as user_name,
            MAX(cash_register_data.cash_register_user) as cash_register_user,
            MAX(workers.name) as worker_name,
            MAX(cash_register_data.date) as date,
            MAX(cash_register_data.status) as status,
            MAX(cash_register_data.updated_at) as updated_at,
            COUNT(notes.id) AS notes_count'
        );

        $query = $query->join('workers', 'cash_register_data.worker_id', '=', 'workers.id');

        $query = $query->leftJoin('notes', 'cash_register_data.id', '=', 'notes.cash_register_data_id');

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

        $query = $query->groupBy(['id']);

        $query = $query->orderBy('date', 'desc');

        $paginator = $query->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $query->paginate(5, ['*'], 'page', 1);
        }

        $columns = [
            "Nro",
            "Usuario creador",
            "Nombre caja",
            'Cajero/a',
            "Fecha cierre",
            "Estatus",
            "Última modificación",
            "Opciones"
        ];

        return view('pages.cash-register.index', compact(
            'columns',
            'paginator',
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
                        $acc[] = array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
                    }

                    return $acc;
                }, []);
                DollarCashRecord::insert($data);
            }

            if (array_key_exists('pago_movil_record', $validated)){
                $data = array_reduce($validated['pago_movil_record'], function($acc, $value) use ($cash_register_data){
                    if ($value > 0){
                        $acc[] = array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
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
                
                foreach($validated['point_sale_bs'] as &$record){
                    $record['cash_register_data_id'] = $cash_register_data->id;
                }
                
                PointSaleBsRecord::insert($validated['point_sale_bs']);
            }

            if (array_key_exists('notes', $validated)){
                
                $data = array_reduce($validated['notes'], function($acc, $note) use ($cash_register_data){
                    if 
                    (!is_null($note['description']) && $note['description'] !== ''){
                        $acc[] = array(
                            'title' => $note['title'],
                            'description' => $note['description'],
                            'cash_register_data_id' => $cash_register_data->id);
                    }

                    return $acc;
                }, []);
                
                Note::insert($data);
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
                        $acc[] = array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
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
        $pago_movil_bs_records = $cash_register_data->pago_movil_bs_records;
        $bs_denomination_records = $cash_register_data->bs_denomination_records;
        $dollar_denomination_records = $cash_register_data->dollar_denomination_records;
        $zelle_records = $cash_register_data->zelle_records;
        $notes = $cash_register_data->notes;
        
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
                    ->select('point_sale_bs_records_2.bank_name')
                    ->from('point_sale_bs_records_2')
                    ->where('point_sale_bs_records_2.cash_register_data_id', '=', $cash_register_data->id)
                    ->groupBy('point_sale_bs_records_2.bank_name');
            })
            ->get();

        $point_sale_bs_records = $cash_register_data
            ->point_sale_bs_records()
            ->orderBy('bank_name')
            ->get();

        // Total amounts
        $total_dollar_cash = $dollar_cash_records->reduce(function($carry, $el){
            return $carry + $el->amount;
        }, 0);

        $total_pago_movil_bs = $pago_movil_bs_records->reduce(function($carry, $el){
            return $carry + $el->amount;
        }, 0);

        $total_point_sale_bs = $point_sale_bs_records->reduce(function($carry, $el){
            return $carry + $el->cancel_debit + $el->cancel_credit + $el->cancel_amex + $el->cancel_todoticket;
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
            'total_pago_movil_bs',
            'total_point_sale_bs',
            'total_dollar_denominations',
            'total_bs_denominations',
            'total_zelle',
            'dollar_cash_records',
            'pago_movil_bs_records',
            'point_sale_dollar_record',
            'point_sale_bs_records',
            'banks',
            'bs_denomination_records',
            'dollar_denomination_records',
            'zelle_records',
            'cash_registers_id_arr',
            'cash_registers_workers_id_arr',
            'date',
            'old_dollar_exchange',
            'notes'
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

            $cash_register_data->pago_movil_bs_records()->upsert($data, ['id'], ['amount']);
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

        if (!key_exists('point_sale_bs', $validated) && $point_sale_bs_records_coll->count() > 0){
            $point_sale_bs_records_coll
                ->each(function($item, $key){
                    $item->delete();
                });

        } else if(key_exists('point_sale_bs', $validated)){
            
            $diff = $point_sale_bs_records_coll->count() - count($validated['point_sale_bs']);

            if ($diff > 0){
                $to_delete = $point_sale_bs_records_coll->splice(0, $diff);
                $to_delete->each(function($item, $key){
                    $item->delete();
                });
            }

            // return print_r( $validated['point_sale_bs']);
            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'pointSaleBsRecordsColsToUpdate',
                $point_sale_bs_records_coll->toArray(),
                $validated['point_sale_bs']
            );
            
            $cash_register_data->point_sale_bs_records()->upsert($data, ['id'], [
                'cancel_debit',
                'cancel_credit',
                'cancel_amex',
                'cancel_todoticket',
                'bank_name'
            ]);
        }

        // Update Notes Records
        $notes_coll = $cash_register_data->notes;

        $notes = array_reduce($validated['notes'], function($acc, $note){
            if (!is_null($note['description']) && $note['description'] !== ''){
                $acc[] = array(
                    'title' => $note['title'],
                    'description' => $note['description'],
                );
            }

            return $acc;
        }, []);

        if (count($notes) === 0 && $notes_coll->count() > 0){
            $notes_coll
                ->each(function($item, $key){
                    $item->delete();
                });

        } else {
            
            $diff = $notes_coll->count() - count($notes);

            if ($diff > 0){
                $to_delete = $notes_coll->splice(0, $diff);
                $to_delete->each(function($item, $key){
                    $item->delete();
                });
            }

            $data = $this->mergeOldAndNewValues(
                $cash_register_data_id,
                'notesColsToUpdate',
                $notes_coll->toArray(),
                $notes
            );
            
            $cash_register_data->notes()->upsert($data, ['id'], [
                'title',
                'description',
            ]);
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

    // Metodo para agregar los metodos de pagos electronicos en cajas que no tuvieron ningun movimiento
    // de este tipo, para un intervalo de tiempo.
    private function completeEpaymentMethodsToCashUserForInteval($totals_from_safact, $totals_e_payment, $payment_methods){
        $default_e_payment_values = $payment_methods->keys()->reduce(function($carry, $item){
            $carry[$item] = [];
            $carry[$item]['bs'] = 0.00;
            $carry[$item]['dollar'] = 0.00;

            return $carry;
        }, []);

        foreach($totals_from_safact as $key_user => $dates){
            if (array_key_exists($key_user, $totals_e_payment)){
                foreach ($dates as $key_date => $date){ 
                    if (!array_key_exists($key_date, $totals_e_payment[$key_user])){
                        $totals_e_payment[$key_user][$key_date] = $default_e_payment_values;
                    }
                }
            } else {
                $totals_e_payment[$key_user] = [];
                foreach ($dates as $key_date => $date){
                    $totals_e_payment[$key_user][$key_date] = $default_e_payment_values;
                }
            }
        }

        return $totals_e_payment;
    }

    // Metodo para agregar los metodos de pagos electronicos en cajas que no tuvieron ningun movimiento
    // de este tipo.
    private function completeEpaymentMethodsToCashUserForRecord($user, $date, $totals_e_payment, $payment_methods){
        $default_e_payment_values = $payment_methods->keys()->reduce(function($carry, $item){
            $carry[$item] = [];
            $carry[$item]['bs'] = 0.00;
            $carry[$item]['dollar'] = 0.00;

            return $carry;
        }, []);

        if (!array_key_exists($user, $totals_e_payment)){
            $totals_e_payment[$user][$date] = $default_e_payment_values;
        }

        return $totals_e_payment;
    }

    private function getPaymentMethods(){
        return DB::table('payment_methods')->orderByRaw("CodPago asc")->get()->groupBy(['CodPago']);
    }

    public function singleRecordPdf(CashRegisterRepository $cash_register_repo, BillRepository $bill_repo, PrintSingleCashRegisterRequest $request){
        
        $id = $request->route('id');
       
        $cash_register_totals = $cash_register_repo->getTotals($id);
        $user =  $cash_register_totals->cash_register_user;
        $date = date('Y-m-d', strtotime( $cash_register_totals->date));
        
        $totals_from_safact = $cash_register_repo->getTotalsFromSafact($cash_register_totals->date,
            $cash_register_totals->date, $cash_register_totals->cash_register_user)->first();

        $payment_methods = $this->getPaymentMethods();

        $totals_e_payment =  $cash_register_repo
            ->getTotalsEPaymentMethods($cash_register_totals->date, $cash_register_totals->date,
                $cash_register_totals->cash_register_user)
            ->groupBy(['CodUsua', 'FechaE']);
        
        $totals_e_payment  = $this
            ->mapEPaymentMethods($totals_e_payment, $payment_methods);

        $vuelto_by_user = $bill_repo
            ->getVueltosByUser($date, $date, $user);

        // Completar las cajas con sus respectivos metodos de pago que no tuvieron operacion con pagos electronicos
        $totals_e_payment = $this->completeEpaymentMethodsToCashUserForRecord($user, $date, $totals_e_payment, $payment_methods);
        
        $differences = [
            'dollar_cash' => round($cash_register_totals->total_dollar_cash - $totals_from_safact->dolares, 2),
            'bs_cash' => round($cash_register_totals->total_bs_denominations - 
                ($totals_from_safact->bolivares - ($vuelto_by_user->count() > 0 ? $vuelto_by_user->first()->MontoBsEfect : 0)), 2),
            'pago_movil_bs' => round($cash_register_totals->total_pago_movil_bs - $totals_e_payment[$user][$date]['05']['bs'], 2),
            'point_sale_bs' => round(($cash_register_totals->total_point_sale_bs - ($totals_e_payment[$user][$date]['01']['bs'] 
                    + $totals_e_payment[$user][$date]['02']['bs'] + $totals_e_payment[$user][$date]['03']['bs']
                    + $totals_e_payment[$user][$date]['04']['bs'])), 2),
            'point_sale_dollar' => round($cash_register_totals->total_point_sale_dollar - $totals_e_payment[$user][$date]['08']['dollar'], 2),
            'zelle' => round($cash_register_totals->total_zelle - $totals_e_payment[$user][$date]['07']['dollar'], 2),
            'bs_denominations' => round($cash_register_totals->total_bs_denominations - 
                ($totals_from_safact->bolivares - ($vuelto_by_user->count() > 0 ? $vuelto_by_user->first()->MontoBsEfect : 0)), 2),
            'dollar_denominations' => round(($cash_register_totals->total_dollar_denominations - $totals_from_safact->dolares) - 
                ($vuelto_by_user->count() > 0 ? ($vuelto_by_user->first()->MontoDivEfect + $vuelto_by_user->first()->MontoDivPM) : 0), 2)
        ];

        $cash_register_data = CashRegisterData::find($id);
       
        $denominations_dollar = $cash_register_data->dollar_denomination_records;
        $denominations_bolivar = $cash_register_data->bs_denomination_records;

        $notes = $cash_register_data->notes;

        $currency_signs = [
            'dollar' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')),
            'bs' => config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR'))
        ];

        $pdf = App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('pdf.cash-register.single-record', compact(
            'cash_register_totals',
            'totals_e_payment',
            'totals_from_safact',
            'denominations_dollar',
            'denominations_bolivar',
            'vuelto_by_user',
            'differences',
            'currency_signs',
            'notes',
            'user',
            'date'
        ))
            ->setOptions([
                'defaultFont' => 'sans-serif',
            ]);
        return $pdf->stream('arqueo-caja_' . $cash_register_totals->cash_register_user . '_' . $cash_register_totals->date . '.pdf');
    }

    public function intervalRecordPdf(CashRegisterRepository $cash_register_repo, BillRepository $bill_repo, PrintIntervalCashRegisterRequest $request){
        
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        // Get database data
        $cash_registers_totals = $cash_register_repo
            ->getTotalsByInterval($start_date, $end_date)
            ->groupBy(['cash_register_user', 'date']);
        
        // Get Saint data
        $totals_from_safact = $cash_register_repo
            ->getTotalsFromSafact($start_date, $end_date)
            ->groupBy(['CodUsua', 'FechaE']);

        $payment_methods = $this->getPaymentMethods();

        $totals_e_payment =  $cash_register_repo
            ->getTotalsEPaymentMethods($start_date, $end_date)
            ->groupBy(['CodUsua', 'FechaE']);
        
        // Completar los metodos de pagos que no tuvieron registros en cada caja
        $totals_e_payment  = $this
            ->mapEPaymentMethods($totals_e_payment, $payment_methods);

        // Completar las cajas con sus respectivos metodos de pago que no tuvieron operacion con pagos electronicos
        $totals_e_payment = $this->completeEpaymentMethodsToCashUserForInteval($totals_from_safact, $totals_e_payment, $payment_methods);

        $vuelto_by_users = $bill_repo
            ->getVueltosByUser($start_date, $end_date)
            ->groupBy(['CodUsua', 'FechaE']);

        $differences = $this->calculateDiffToSaint($totals_from_safact, $totals_e_payment, $cash_registers_totals, $vuelto_by_users);
    
        $dollar_denominations = DB::table('cash_register_data')
            ->join('dollar_denomination_records', 'cash_register_data.id', '=', 'dollar_denomination_records.cash_register_data_id')
            ->whereRaw("cash_register_data.date BETWEEN ? AND ?", [$start_date, $end_date])
            ->orderByRaw("cash_register_data.cash_register_user asc, cash_register_data.date asc, dollar_denomination_records.denomination asc")
            ->get()
            ->groupBy(['cash_register_user', 'date']);

        $bs_denominations = DB::table('cash_register_data')
            ->join('bs_denomination_records', 'cash_register_data.id', '=', 'bs_denomination_records.cash_register_data_id')
            ->whereRaw("cash_register_data.date BETWEEN ? AND ?", [$start_date, $end_date])
            ->orderByRaw("cash_register_data.cash_register_user asc, cash_register_data.date asc, bs_denomination_records.denomination asc")
            ->get()
            ->groupBy(['cash_register_user', 'date']);

        $notes = DB::table('cash_register_data')
            ->join('notes', 'cash_register_data.id', '=', 'notes.cash_register_data_id')
            ->whereRaw("cash_register_data.date BETWEEN ? AND ?", [$start_date, $end_date])
            ->orderByRaw("cash_register_data.cash_register_user asc, cash_register_data.date asc")
            ->get()
            ->groupBy(['cash_register_user', 'date']);

        $totals_bs_denominations = $this->sumSubTotalDenomination($bs_denominations);
        $total_dollar_denominations = $this->sumSubTotalDenomination($dollar_denominations);

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $saint_totals = $this->joinSaintMoneyEntranceCollections($totals_from_safact,
            $totals_e_payment);

        $total_quantity_dollar_denominations = $this->sumQuantityCashRegisterDenominations($dollar_denominations);
        $total_quantity_bs_denominations = $this->sumQuantityCashRegisterDenominations($bs_denominations);
      
        $total_bs_denominations_summary = 0;
        foreach($total_quantity_bs_denominations as $denomination => $quantity){
            $total_bs_denominations_summary += floatval($denomination) * $quantity;
        }

        $total_dollar_denominations_summary = 0;
        foreach($total_quantity_dollar_denominations as $denomination => $quantity){
            $total_dollar_denominations_summary += floatval($denomination) * $quantity;
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('pdf.cash-register.interval-record', compact(
            'saint_totals',
            'cash_registers_totals',
            'differences',
            'vuelto_by_users',
            'dollar_denominations',
            'bs_denominations',
            'totals_bs_denominations',
            'total_dollar_denominations',
            'total_quantity_dollar_denominations',
            'total_quantity_bs_denominations',
            'total_bs_denominations_summary',
            'total_dollar_denominations_summary',
            'notes',
            'start_date',
            'end_date'
        ))
        ->setOptions([
            'defaultFont' => 'sans-serif',
            'isPhpEnabled' => true
        ]);


        return $pdf->stream('arqueos-de-caja_' . $start_date . '_' . $end_date . '.pdf');
    }

    private function sumQuantityCashRegisterDenominations($denomination_records){
        $total_quantity = [];
        foreach($denomination_records as $key_user => $dates){
            foreach ($dates as $key_date => $date){
                
                $date->each(function($item, $key) use (&$total_quantity){
                    if (!key_exists(number_format($item->denomination, 2,'.',''), $total_quantity)){
                        $total_quantity[number_format($item->denomination, 2,'.','')] = 0;
                    }
                    $total_quantity[number_format($item->denomination, 2,'.','')] += $item->quantity;
                });
            }
        }

        return $total_quantity;
    }

    private function joinSaintMoneyEntranceCollections($totals_from_safact, $totals_e_payment){
        $saint_totals = [];
       
        foreach($totals_from_safact as $key_user => $dates){
            $saint_totals[$key_user] = [];
     
            foreach ($dates as $key_date => $date){
                $saint_totals[$key_user][$key_date] = [];
                $saint_totals[$key_user][$key_date]['bolivares'] = $date->first()->bolivares;
                $saint_totals[$key_user][$key_date]['dolares'] = $date->first()->dolares;
                $saint_totals[$key_user][$key_date] = array_merge($saint_totals[$key_user][$key_date], $totals_e_payment[$key_user][$key_date]);
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

    private function calculateDiffToSaint($totals_from_safact, $totals_e_payment, $cash_registers, $money_back_by_users){
        $differences = [];

        $totals_from_safact->each(function($dates, $key_user) use ($cash_registers, &$differences, $money_back_by_users){
            $differences[$key_user] = [];
            $money_back_by_user = $money_back_by_users->has($key_user) ? $money_back_by_users[$key_user] : null;
            
            if ($cash_registers->has($key_user)){
                $dates->each(function($date, $key_date) use (&$differences, $key_user, $cash_registers, $money_back_by_user){

                    $money_back_by_user_date = !is_null($money_back_by_user) && $money_back_by_user->has($key_date) ? $money_back_by_user[$key_date] : null;

                    if ($cash_registers[$key_user]->has($key_date)){
                        $differences[$key_user][$key_date] = [];
                        $differences[$key_user][$key_date]['dollar_cash'] = round($cash_registers[$key_user][$key_date]->first()->total_dollar_cash - $date[0]->dolares, 2);
                        $differences[$key_user][$key_date]['bs_denominations'] = round($cash_registers[$key_user][$key_date]->first()->total_bs_denominations - (
                            $date[0]->bolivares - (!is_null($money_back_by_user_date) ? $money_back_by_user_date->first()->MontoBsEfect : 0)), 2);
                        $differences[$key_user][$key_date]['dollar_denominations'] = round(($cash_registers[$key_user][$key_date]->first()->total_dollar_denominations - $date[0]->dolares)
                            - (!is_null($money_back_by_user_date) ? ($money_back_by_user_date->first()->MontoDivEfect + $money_back_by_user_date->first()->MontoDivPM) : 0), 2);
                    } else {
                        $differences[$key_user][$key_date] = [];
                        $differences[$key_user][$key_date]['dollar_cash'] = $date[0]->dolares * -1;
                        $differences[$key_user][$key_date]['bs_denominations'] = ($date[0]->bolivares - (!is_null($money_back_by_user_date) ? $money_back_by_user_date->first()->MontoBsEfect : 0)) * -1;
                        $differences[$key_user][$key_date]['dollar_denominations'] = ($date[0]->dolares + (!is_null($money_back_by_user_date) ? ($money_back_by_user_date->first()->MontoDivEfect + $money_back_by_user_date->first()->MontoDivPM) : 0)) * -1;

                    }
                });
            } else {
                $dates->each(function($date, $key_date) use (&$differences, $key_user, $money_back_by_user){
                    $money_back_by_user_date = !is_null($money_back_by_user) && $money_back_by_user->has($key_date) ? $money_back_by_user[$key_date] : null;

                    $differences[$key_user][$key_date] = [];
                    $differences[$key_user][$key_date]['dollar_cash'] = $date[0]->dolares * -1;
                    $differences[$key_user][$key_date]['bs_denominations'] = ($date[0]->bolivares -  (!is_null($money_back_by_user_date) ? $money_back_by_user_date->first()->MontoBsEfect : 0)) * -1;
                    $differences[$key_user][$key_date]['dollar_denominations'] = ($date[0]->dolares + (!is_null($money_back_by_user_date) ? ($money_back_by_user_date->first()->MontoDivEfect + $money_back_by_user_date->first()->MontoDivPM) : 0)) * -1;
                });
            }
        });

        foreach($totals_e_payment as $key_user => $dates){
            
            // if (!array_key_exists($key_user, $differences)){
            //     $differences[$key_user]  = [];
            // }

            if ($cash_registers->has($key_user)){
                
                foreach($dates as $key_date => $date){
                    if ($cash_registers[$key_user]->has($key_date)){
                        $differences[$key_user][$key_date]['point_sale_bs'] = round($cash_registers[$key_user][$key_date]->first()->total_point_sale_bs - ($date['01']['bs'] + $date['02']['bs']
                                + $date['03']['bs'] + $date['04']['bs']), 2);
                        $differences[$key_user][$key_date]['point_sale_dollar'] = round($cash_registers[$key_user][$key_date]->first()->total_point_sale_dollar - $date['08']['dollar'], 2);
                        $differences[$key_user][$key_date]['pago_movil_bs'] = round($cash_registers[$key_user][$key_date]->first()->total_pago_movil_bs - $date['05']['bs'], 2);
                        $differences[$key_user][$key_date]['zelle'] = round($cash_registers[$key_user][$key_date]->first()->total_zelle -  $date['07']['dollar'], 2);
                    } else {
                        // if (!array_key_exists($key_date, $differences[$key_user])){
                        //     $differences[$key_user][$key_date] = [];
                        // }
                        $differences[$key_user][$key_date] = [];
                        $differences[$key_user][$key_date]['point_sale_bs'] = ($date['01']['bs'] + $date['02']['bs'] 
                                + $date['03']['bs'] + $date['04']['bs']) * -1;
                        $differences[$key_user][$key_date]['point_sale_dollar'] = $date['08']['dollar'] * -1;
                        $differences[$key_user][$key_date]['pago_movil_bs'] = $date['05']['bs'] * -1;
                        $differences[$key_user][$key_date]['zelle'] = $date['07']['dollar'] * -1;
                    }
                }
            } else {
                foreach($dates as $key_date => $date){
                    // if (!array_key_exists($key_date, $differences[$key_user])){
                    //     $differences[$key_user][$key_date] = [];
                    // }
                    $differences[$key_user][$key_date] = [];

                    $differences[$key_user][$key_date]['point_sale_bs'] = ($date['01']['bs'] + $date['02']['bs']
                            + $date['03']['bs'] + $date['04']['bs']) * -1;
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

    private function pointSaleBsRecordsColsToUpdate($old_record, $new_record, $parent_id){
        return [
            'id' => $old_record ? $old_record['id'] : null,
            'cancel_debit' => $new_record[0]['cancel_debit'],
            'cancel_credit' => $new_record[0]['cancel_credit'],
            'cancel_amex' => $new_record[0]['cancel_amex'],
            'cancel_todoticket' => $new_record[0]['cancel_todoticket'],
            'bank_name' => $new_record[0]['bank_name'],
            'cash_register_data_id' => $parent_id
        ];
    }

    private function notesColsToUpdate($old_record, $new_record, $parent_id){
        return [
            'id' => $old_record ? $old_record['id'] : null,
            'title' => $new_record[0]['title'],
            'description' => $new_record[0]['description'],
            'cash_register_data_id' => $parent_id
        ];
    }
}
