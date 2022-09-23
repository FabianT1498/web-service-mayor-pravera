<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Repositories\BillsPayableRepository;
use App\Repositories\BillSchedulesRepository;
use App\Repositories\ProviderRepository;

use App\Http\Requests\LinkBillPayableToScheduleRequest;
use App\Http\Requests\LinkBillPayableGroupToScheduleRequest;
use App\Http\Requests\StoreBillPayablePaymentRequest;
use App\Http\Requests\UpdateBillPayableTasaRequest;
use App\Http\Requests\UpsertBillPayableRequest;
use App\Http\Requests\ShowBillPayableRequest;
use App\Http\Requests\ShowBillPayableGroupRequest;
use App\Http\Requests\StoreBillPayableGroupRequest;
use App\Http\Requests\UpdateBillPayableGroupRequest;
use App\Http\Requests\StoreBillPayableGroupPaymentRequest;
use App\Http\Requests\UpdateBillPayableGroupTasaRequest;

use App\Models\BillPayable;
use App\Models\BillPayablePayment;
use App\Models\BillsPayablePayments;
use App\Models\BillPayablePaymentBs;
use App\Models\BillPayablePaymentDollar;
use App\Models\BillPayableGroup;

use App\Http\Traits\SessionTrait;
use App\Http\Traits\AmountCurrencyTrait;
use App\Http\Traits\StringTrait;

class BillsPayableController extends Controller
{
    private $flasher = null;

    use SessionTrait;
    use AmountCurrencyTrait;
    use StringTrait;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, BillsPayableRepository $repo, BillSchedulesRepository $repo_schedule){

        $this->setSession($request, 'current_module', 'bill_payable');

        $bill_action = $request->query('bill_action', '');

        
        $bill_action_val = config('constants.BILL_PAYABLE_ACTION.' . $bill_action);
        
        $bill_action_mess = config('constants.BILL_PAYABLE_ACTION_MESS.' . $bill_action_val);
        
        if (isset($bill_action_mess) && $bill_action_mess !== ''){

            if ($bill_action_val === 2 || $bill_action_val === 3){
                $this->flasher->addError($bill_action_mess);
            } else {
                $this->flasher->addSuccess($bill_action_mess);
            }
        }

        // Filter params
        $is_scheduled_bill = $request->query('is_scheduled_bill', 'yes');

        $nro_doc = $request->query('nro_doc', '');
        $end_emission_date = $request->query('end_emission_date', Carbon::now()->format('d-m-Y'));
        $cod_prov = $request->query('cod_prov', '');
        $descrip_prov =  $request->query('cod_prov_value', '');
        
        // Current page
        $page = $request->query('page', '');

        $new_end_emission_date = '';

        $bill_types = array_map(function($val, $key){
            return (object) array("key" => $key, "value" => $val);
        }, config('constants.BILL_PAYABLE_TYPE'), array_keys(config('constants.BILL_PAYABLE_TYPE')));

        $bill_type = $request->query('bill_type', $bill_types[0]->key);

        $bill_currencies =  array_map(function($val){
            return (object) array("key" => $val, "value" => $val);
        }, array_keys(config('constants.CURRENCIES')));

        $bill_currency = $request->query('bill_currency', $bill_currencies[0]->key);

        $is_dollar = config('constants.CURRENCIES.' . $bill_currency) === config('constants.CURRENCIES.BOLIVAR') ? 0 : 1;

        if(is_null($end_emission_date) || $end_emission_date === ''){
            $now = Carbon::now();
            $end_emission_date =  $now->format('d-m-Y');
            $new_end_emission_date = $now->format('Y-m-d');
        } else {
            $new_end_emission_date = date('Y-m-d', strtotime($end_emission_date));
        }

        $paginator = null;

        $is_scheduled_bill_val = $is_scheduled_bill === 'yes' ? 1 : 0;

        if ($is_scheduled_bill_val === 1){

            $paginator = $repo->getBillsPayable($is_dollar, $new_end_emission_date, $bill_type, $nro_doc, $cod_prov, $is_scheduled_bill_val)->paginate(5);

            if ($paginator->lastPage() < $page){
                $paginator = $repo->getBillsPayable($is_dollar, $new_end_emission_date, $bill_type, $nro_doc, $cod_prov, $is_scheduled_bill_val)->paginate(5, ['*'], 'page', 1);
            }

            $data = array_map(function($item) {
                $datetime1 = new \DateTime();
                $datetime2 = new \DateTime($item->FechaE);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a'); //now do whatever you like with $days

                return (object) [
                    'NumeroD' => $item->NumeroD,
                    'CodProv' => $item->CodProv,
                    'Descrip' => $item->Descrip,
                    'FechaE' => $item->FechaE,
                    'FechaPosteo' => $item->FechaE,
                    'esDolar' => $item->esDolar,
                    'MontoTotal' => number_format($item->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS." . ($item->esDolar ? "dollar" : "bolivar")),
                    'MontoPagar' => number_format($item->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS.dollar"),
                    'MontoPagado' => isset($item->MontoPagado) ? $this->formatAmount($item->MontoPagado) : 0.00,
                    'Tasa' => $this->formatAmount($item->Tasa),
                    'Estatus' => isset($item->Status)  ? config("constants.BILL_PAYABLE_STATUS." . $item->Status) : config("constants.BILL_PAYABLE_STATUS.NOTPAID"),
                    'DiasTranscurridos' => $days,
                    'BillPayableSchedulesID' => isset($item->BillPayableSchedulesID) ? $item->BillPayableSchedulesID : null,
                    'BillPayableGroupsID' => isset($item->BillPayableGroupsID) ? $item->BillPayableGroupsID : null,
                ];
            }, $paginator->items());
            
        } else {
            
            $paginator = $repo->getBillsPayableFromSaint($is_dollar, $new_end_emission_date, $bill_type, $nro_doc, $cod_prov)->paginate(5);

            if ($paginator->lastPage() < $page){
                $paginator = $repo->getBillsPayableFromSaint($is_dollar, $new_end_emission_date, $bill_type, $nro_doc, $cod_prov)->paginate(5, ['*'], 'page', 1);
            }

            $bills_payable_keys = implode(" OR ", array_map(function($item){
                return "(bills_payable.cod_prov = '" . $item->CodProv . "' AND bills_payable.nro_doc = '" . $item->NumeroD . "')";
            }, $paginator->items()));

            $bills_payable_records = null;

            if ($bills_payable_keys !== ''){
                $bills_payable_records = $repo->getBillsPayableByIds($bills_payable_keys)->take(5)->get()->groupBy(['CodProv', 'NumeroD']);
            }

            $data = array_map(function($item) use ($bills_payable_records){
                
                $record = !is_null($bills_payable_records) && $bills_payable_records->has($item->CodProv) && $bills_payable_records[$item->CodProv]->has($item->NumeroD)
                    ? $bills_payable_records[$item->CodProv][$item->NumeroD]->first()
                    : $item;

                $datetime1 = new \DateTime();
                $datetime2 = new \DateTime($item->FechaPosteo);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');//now do whatever you like with $days

                return (object) [
                    'NumeroD' => $record->NumeroD,
                    'CodProv' => $record->CodProv,
                    'Descrip' => $item->Descrip,
                    'FechaE' => $item->FechaE,
                    'FechaPosteo' => $item->FechaPosteo,
                    'esDolar' => $record->esDolar,
                    'MontoTotal' => number_format($record->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS." . ($record->esDolar ? "dollar" : "bolivar")),
                    'MontoPagar' => number_format($record->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS." . ($record->esDolar ? "dollar" : "bolivar")),
                    'MontoPagado' => isset($record->MontoPagado) ? $this->formatAmount($record->MontoPagado) : 0.00,
                    'Tasa' => floatval($record->Tasa),
                    'Estatus' => isset($record->Status)  ? config("constants.BILL_PAYABLE_STATUS." . $record->Status) : config("constants.BILL_PAYABLE_STATUS.NOTPAID"),
                    'DiasTranscurridos' => $days,
                    'BillPayableSchedulesID' => isset($record->BillPayableSchedulesID) ? $record->BillPayableSchedulesID : null,
                    'BillPayableGroupsID' => isset($record->BillPayableGroupsID) ? $record->BillPayableGroupsID : null
                ];
            }, $paginator->items());
        }
 
        $schedules = $repo_schedule->getBillSchedules()->get()->map(function($item){
            return (object) array("key" => $item->WeekNumber, "value" => "Semana " . $item->WeekNumber);
        });

        $columns = [
            "Numero Fac.",
            "Cod. Proveedor",
            "Proveedor",
            "F. Emision.",
            "F. Posteo",
            'Monto Factura',
            'Monto Pagar ($)',
            'Tasa',
            'Es dolar',
            'Estatus',
            'Dias transcurridos',
            "Opciones"
        ];

        return view('pages.bills-payable.index', compact(
            'columns',
            'paginator',
            'data',
            'is_scheduled_bill',
            'nro_doc',
            'bill_type',
            'bill_types',
            'cod_prov',
            'descrip_prov',
            'end_emission_date',
            'page',
            'schedules',
            'bill_currencies',
            'bill_currency',
        ));
    }

    public function show(ShowBillPayableRequest $request, BillsPayableRepository $repo){

        $payment_dollar_table_cols = [
            "Fecha pago",
            'Método pago',
            'Fecha retiro',
            'Monto'
        ];

        $payment_bs_table_cols = [
            "Fecha pago",
            'Banco',
            'Nro. referencia',
            'Tasa',
            "Monto",
            "Monto ($)"
        ];

        $payment_currencies =  array_map(function($val){
            return (object) array("key" => $val, "value" => $val);
        }, array_keys(config('constants.CURRENCIES')));

        $payment_currency = $request->query('payment_currency', $payment_currencies[0]->key);

        $today_date = Carbon::now()->format('d-m-Y');
        
        $bill = $repo->getBillPayable($request->numero_d, $request->cod_prov);

        $bill->Tasa = $this->formatAmount($bill->Tasa);
        $bill->MontoPagado = $this->formatAmount($bill->MontoPagado);

        $bill_payments_bs = $repo->getBillPayablePaymentsBs($request->numero_d, $request->cod_prov)->get();
        $bill_payments_dollar = $repo->getBillPayablePaymentsDollar($request->numero_d, $request->cod_prov)->get();

        $bill_payments_bs = $bill_payments_bs->map(function($record){
            return (object) [
                'NumeroD' => $record->NumeroD,
                'CodProv' => $record->CodProv,
                'Amount' => number_format($record->Amount, 2) . " " . config("constants.CURRENCY_SIGNS." . ($record->esDolar ? "dollar" : "bolivar")),
                'DollarAmount' => number_format($record->Amount / $record->Tasa, 2) . " " . config("constants.CURRENCY_SIGNS.dollar"),
                'Tasa' => number_format($record->Tasa, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar"),
                'BankName' => $record->BankName,
                'RefNumber' => $record->RefNumber,
                'Date' => date('d-m-Y', strtotime($record->Date))
            ];
        });

        $bill_payments_dollar = $bill_payments_dollar->map(function($record){
            return (object) [
                'NumeroD' => $record->NumeroD,
                'CodProv' => $record->CodProv,
                'Amount' => number_format($record->Amount, 2) . " " . config("constants.CURRENCY_SIGNS." . ($record->esDolar ? "dollar" : "bolivar")),
                'Date' => date('d-m-Y', strtotime($record->Date)),
                'PaymentMethod' => $record->PaymentMethod,
                'RetirementDate' => date('d-m-Y', strtotime($record->RetirementDate)),
            ];
        });

        $banks = DB::connection('web_services_db')
            ->table('banks')
            ->select('name')
            ->get()
            ->map(function($item){
                return (object) array('key' => $item->name, 'value' => $item->name);
            });

        $foreign_currency_payment_methods = array_map(function($val, $key){
            return (object) array("key" => $key, "value" => $val);
        }, config('constants.FOREIGN_CURRENCY_BILL_PAYMENT_METHODS'), array_keys(config('constants.FOREIGN_CURRENCY_BILL_PAYMENT_METHODS')));

        return view('pages.bills-payable.show', compact(
            'bill',
            'foreign_currency_payment_methods',
            'bill_payments_bs',
            'bill_payments_dollar',
            'payment_dollar_table_cols',
            'payment_bs_table_cols',
            'banks',
            'today_date',
            'payment_currencies',
            'payment_currency'
        ));
    }

    public function showBillPayableGroup(ShowBillPayableGroupRequest $request, BillsPayableRepository $repo){

        $is_group_payment = 1;

        $today_date = Carbon::now()->format('d-m-Y');

        $bills_payable_columns = [
            "Numero Documento",
            "Proveedor",
            "Monto total",
            "Tasa",
        ];

        $payment_dollar_table_cols = [
            "Fecha pago",
            'Método pago',
            'Fecha retiro',
            'Monto'
        ];

        $payment_bs_table_cols = [
            "Fecha pago",
            'Banco',
            'Nro. referencia',
            'Tasa',
            "Monto",
            "Monto ($)"
        ];
 
        $group = $repo->getBillPayableGroupByID($request->id);

        $group->MontoPagado =  floor($group->MontoPagado * 100) / 100;

        // Obtiene la tasa de la ultima factura
        $last_tasa = $repo->getLastBillPayableTasaByGroupID($request->id);
        
        // Facturas del grupo
        $bs_bills_payable = $repo->getBillsPayableByGroupID($request->id);

        $dollar_bills_payable = $repo->getBillsPayableByGroupID($request->id, 1);

        // Pagos del lote de facturas
        $group_payments_dollar = $repo->getBillPayablePaymentsByGroupID($request->id, 1);
        $group_payments_bs = $repo->getBillPayablePaymentsByGroupID($request->id, 0);

        $group_payments_bs = $group_payments_bs->map(function($record){
            return (object) [
                'Amount' => number_format($record->Amount, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar"),
                'DollarAmount' => number_format($record->DollarAmount, 2) . " " . config("constants.CURRENCY_SIGNS.dollar"),
                'Tasa' => number_format($record->Tasa, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar"),
                'BankName' => $record->BankName,
                'RefNumber' => $record->RefNumber,
                'Date' => date('d-m-Y', strtotime($record->Date))
            ];
        });

        $group_payments_dollar = $group_payments_dollar->map(function($record){
            return (object) [
                'Amount' => number_format($record->Amount, 2) . " " . config("constants.CURRENCY_SIGNS.dollar"),
                'Date' => date('d-m-Y', strtotime($record->Date)),
                'PaymentMethod' => $record->PaymentMethod,
                'RetirementDate' => date('d-m-Y', strtotime($record->RetirementDate)),
            ];
        });

        $banks = DB::connection('web_services_db')
            ->table('banks')
            ->select('name')
            ->get()
            ->map(function($item){
                return (object) array('key' => $item->name, 'value' => $item->name);
            });

        $foreign_currency_payment_methods = array_map(function($val, $key){
            return (object) array("key" => $key, "value" => $val);
        }, config('constants.FOREIGN_CURRENCY_BILL_PAYMENT_METHODS'), array_keys(config('constants.FOREIGN_CURRENCY_BILL_PAYMENT_METHODS')));

        return view('pages.bill-payable-groups.show', compact(
            'group',
            'foreign_currency_payment_methods',
            'group_payments_bs',
            'group_payments_dollar',
            'payment_dollar_table_cols',
            'payment_bs_table_cols',
            'banks',
            'is_group_payment',
            'bs_bills_payable',
            'dollar_bills_payable',
            'last_tasa',
            'today_date',
            'bills_payable_columns'
        ));
    }

    public function storePayment(StoreBillPayablePaymentRequest $request, BillsPayableRepository $repo){

        $validated = $request->validated();

        $validated['is_dollar'] = $validated['payment_currency'] === array_keys(config('constants.CURRENCIES'))[1] ? '1' : '0';

        $bill_payment = BillPayablePayment::create($validated);

        if ($bill_payment){

            $validated['bill_payments_id'] = $bill_payment->id;
            
            $bill_payment_child = null;

            $bill = $repo->getBillPayable($validated['nro_doc'], $validated['cod_prov']);

            $bill_amount_to_pay_ref = floatval($bill->MontoPagar . 'El');

            $payment_amount_ref = 0;

            if ($validated['is_dollar'] === '0'){
                $payment_amount_ref = floor(($validated['amount'] / $validated['tasa']) * 100) / 100;
                $bill_payment_child = BillPayablePaymentBs::create($validated);
               
            } else if ($validated['is_dollar'] === '1') {
                $payment_amount_ref = $validated['amount'];
                $validated['payment_method'] = $validated['foreign_currency_payment_method'];
                unset($validated['foreign_currency_payment_method']);
                $bill_payment_child = BillPayablePaymentDollar::create($validated);
            }

            if ($bill_payment_child){
                
                $diff = floor(($bill_amount_to_pay_ref - $payment_amount_ref) * 100) / 100;
                
                $bill_status_change = false;
    
                if ($diff <= 0){
                    $bill_model = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$validated['nro_doc'], $validated['cod_prov']])->first();
                    $bill_model->status = array_keys(config("constants.BILL_PAYABLE_STATUS"))[1];
                    $bill_status_change = $bill_model->save();
                }

                $bill_payment_record = BillsPayablePayments::create($validated);

                if ($bill_payment_record){
                    $this->flasher->addSuccess("El pago fue creado exitosamente " . ($bill_status_change ? "y la factura fue pagada completamente !" : "!"));
                } else {
                    $bill_payment->delete();
                    $bill_payment_child->delete();
                    $this->flasher->addError('No se pudo crear el pago para la factura');
                }
                
            } else {
                $bill_payment->delete();
                $this->flasher->addError('No se pudo crear el pago para la factura');
            }

        } else {
            $this->flasher->addError('No se pudo crear el pago para la factura');
        }
        
        return redirect()->route('bill_payable.showBillPayable', 
            ['numero_d' => $validated['nro_doc'], 'cod_prov' => $validated['cod_prov']]);

    }

    public function storeBillPayableGroupPayment(StoreBillPayableGroupPaymentRequest $request, BillsPayableRepository $repo){
        
        $validated = $request->validated();

        $validated['is_dollar'] = key_exists('is_dollar', $request->all()) ? $request->all()['is_dollar'] : '0';

        $validated['is_group_payment'] = 1;

        $bill_payment = BillPayablePayment::create($validated);

        if ($bill_payment){

            $validated['bill_payments_id'] = $bill_payment->id;
            
            $bill_payment_child = null;

            $payment_amount_ref = 0;

            if ($validated['is_dollar'] === '0'){
                $payment_amount_ref = floor(($validated['amount'] / $validated['tasa']) * 100) / 100;
                $bill_payment_child = BillPayablePaymentBs::create($validated);
               
            } else if ($validated['is_dollar'] === '1') {
                $payment_amount_ref = $validated['amount'];
                $validated['payment_method'] = $validated['foreign_currency_payment_method'];
                unset($validated['foreign_currency_payment_method']);
                $bill_payment_child = BillPayablePaymentDollar::create($validated);
            }

            $group_id = $validated['group_id'];
            $group = $repo->getBillPayableGroupByID($group_id);

            $bills_payable_dollar = $repo->getBillsPayableByGroupID($group_id, 1);

            $bills_payable_bs = $repo->getBillsPayableByGroupID($group_id);

            $group_amount_to_pay = floatval($group->MontoPagar . 'El');

            if ($bill_payment_child){
                
                $diff = floor(($group_amount_to_pay - $payment_amount_ref) * 100) / 100;
                
                $bill_group_status_change = false;
                $bill_payable_status_change = true;
    
                if ($diff <= 0){
                    $group_model = BillPayableGroup::whereRaw("id = ?", [$group_id])->first();
                    $group_model->status = array_keys(config("constants.BILL_PAYABLE_STATUS"))[1];
                    $bill_group_status_change = $group_model->save();

                    $bills_payable = BillPayable::whereRaw("bill_payable_groups_id = " . $group_id)->get();
                    foreach($bills_payable as $bill_payable){
                        $bill_payable->status = array_keys(config("constants.BILL_PAYABLE_STATUS"))[1];
                        $bill_payable_status_change = $bill_payable->save();

                        if (!$bill_payable_status_change){
                            $bill_payable_status_change = false;
                            return false;
                        }
                    }
                }

                $payment_saved_successfully = true;

                foreach($bills_payable_dollar as $bill){

                    $data = [
                        'nro_doc' => $bill->NumeroD,
                        'cod_prov' => $bill->CodProv,
                        'bill_payments_id' => $validated['bill_payments_id']
                    ];

                    if(!BillsPayablePayments::create($data)){
                        $payment_saved_successfully = false;
                        return false;
                    }
                }

                if ($payment_saved_successfully){
                    foreach($bills_payable_bs as $bill){
    
                        $data = [
                            'nro_doc' => $bill->NumeroD,
                            'cod_prov' => $bill->CodProv,
                            'bill_payments_id' => $validated['bill_payments_id']
                        ];
    
                        if(!BillsPayablePayments::create($data)){
                            $payment_saved_successfully = false;
                            return false;
                        }
                    }

                    if ($payment_saved_successfully){
                        $this->flasher->addSuccess("El pago fue creado exitosamente " . ($bill_group_status_change ? "y el lote de facturas fue pagado completamente !" : "!"));
                    } else {
                        $this->flasher->addError('No se pudo crear el pago para el lote de factura');
                    }
                    
                } else {
                    $this->flasher->addError('No se pudo crear el pago para el lote de factura');
                }
                
            } else {
                $this->flasher->addError('No se pudo crear el pago para el lote de factura');
            }
        } else {
            $this->flasher->addError('No se pudo crear el pago para el lote de factura');
        }
        
        return redirect()->route('bill_payable.showBillPayableGroup', 
            ['id' => $group_id]);
    }

    public function storeBillPayableGroup(StoreBillPayableGroupRequest $request, BillsPayableRepository $repo){
        
        $validated = $request->validated();

        $bills = array_map(function($item){
            $bill_record = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$item['nro_doc'], $item['cod_prov']])->first();
        
            if (is_null($bill_record)){
                
                $bill_record = BillPayable::create($item);
            }
            
            return $bill_record;

        }, $validated['bills']);

        $group = BillPayableGroup::create($validated['bills'][0]);

        $group_record = null;

        if ($group){
            foreach($bills as $bill){
                $bill->bill_payable_groups_id = $group->id;
                $bill->save();
            }

            $group_record = $repo->getBillPayableGroupByID($group->id);
        }

        return $this->jsonResponse([
            'status' => 200,
            'data' => [
                'bills' => $bills,
                'group' => $group_record
            ]
        ], 200);

    }

    public function updateBillPayableGroup(UpdateBillPayableGroupRequest $request, BillsPayableRepository $repo){
        
        $validated = $request->validated();

        // Verificar cuales facturas no tienen registros
        $bills = array_map(function($item){
            $bill_record = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$item['nro_doc'], $item['cod_prov']])->first();
        
            if (is_null($bill_record)){
                
                // Recuperar informacion de la base de datos de SAINT
                $bill_record = BillPayable::create($item);
            }
            
            return $bill_record;

        }, $validated['bills']);

        $group = $repo->getBillPayableGroupByID($request->id);

        if ($group){
            foreach($bills as $bill){
                if ($bill->bill_payable_groups_id !== $group->ID){
                    $bill->bill_payable_groups_id = $group->ID;
                    $bill->bill_payable_schedules_id = $group->ScheduleID;
                    $bill->save();
                }
            }
        }

        $group = $repo->getBillPayableGroupByID($request->id);

        return $this->jsonResponse([
            'status' => 200,
            'data' => [
                'bills' => $bills,
                'group' => $group
            ]
        ], 200);
    }

    public function updateBillPayableTasa(UpdateBillPayableTasaRequest $request){

        $validated = $request->validated();
 
        $bill = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$validated['nro_doc'], $validated['cod_prov']])->first();

        $bill->tasa = $validated['bill_tasa'];

        if ($bill->save()){
            $this->flasher->addSuccess('La tasa fue actualizada!');
        } else {
            $this->flasher->addError('La tasa no pudo ser actualizada');
        }

        return redirect()->route('bill_payable.showBillPayable', 
            ['numero_d' => $validated['nro_doc'], 'cod_prov' => $validated['cod_prov']]);
    }

    public function storeBillPayable(UpsertBillPayableRequest $request, BillsPayableRepository $repo){

        $validated = $request->validated();

        $nro_doc = $validated['numeroD'];
        $cod_prov = $validated['codProv'];
        $descrip_prov = $validated['provDescrip'];
        $bill_type = $validated['billType'];
        $tasa = $validated['tasa'];
        $is_dollar = $validated['isDollar'];
        $amount = $validated['amount'];
        $emission_date = $validated['fechaE'];
      
        $data = [
            'nro_doc' => $nro_doc,
            'cod_prov' => $cod_prov,
            'descrip_prov' => $descrip_prov,
            'bill_type' => $bill_type,
            'amount' => $amount,
            'is_dollar' => $is_dollar,
            'tasa' => $tasa,
            'emission_date' => $emission_date
        ];
        
        BillPayable::upsert($data,
            ['nro_doc', 'cod_prov'],
            ['amount', 'is_dollar', 'tasa', 'bill_type']);

        return $this->jsonResponse($data, 200);
    }

    public function getBillPayable(Request $request, BillsPayableRepository $repo){

        $numero_d = $request->numero_d;
     
        if ($this->isADateFormatDDMMYYYY($request->numero_d)){
            $numero_d = $this->charReplace($numero_d);
        }

        $bill = $repo->getBillPayable($numero_d, $request->cod_prov);

        return $this->jsonResponse($bill ? [$bill] : [], 200);
    }

    public function getBillPayableGroups(Request $request, BillsPayableRepository $repo){

        $bill_payable_groups = $repo->getBillPayableGroups($request->cod_prov)->get();

        return $this->jsonResponse($bill_payable_groups ? $bill_payable_groups : [], 200);
    }

    public function getBillPayableGroupByID(Request $request, BillsPayableRepository $repo){

        $id = $request->id;
        $group = $repo->getBillPayableGroupByID($id);
        $last_tasa = $repo->getLastBillPayableTasaByGroupID($id);

        return $this->jsonResponse([
            'status' => 200,
            'data' => [
                'group' => $group,
                'last_tasa' => $last_tasa
            ]
        ], 200);
    }

    public function updateBillPayableGroupTasa(UpdateBillPayableGroupTasaRequest $request){

        $validated = $request->validated();

        $group_id = $request->all()['group_id'];
 
        $bills = BillPayable::whereRaw("bill_payable_groups_id = ?", [$group_id])->get();

        $saved_succesfully = true;

        foreach($bills as $bill){
            $bill->tasa = $validated['group_tasa'];
            if(!$bill->save()){
                $saved_succesfully = false;
                return false;
            }
        }

        if (!$saved_succesfully){
            $this->flasher->addError('La tasa no pudo ser actualizada');
        }

        $this->flasher->addSuccess('La tasa fue actualizada!');
        
        return redirect()->route('bill_payable.showBillPayableGroup', 
            ['id' => $group_id]);
    }

    public function linkBillPayableToSchedule(LinkBillPayableToScheduleRequest $request){

        $validated = $request->validated();

        $nro_doc = $validated['numeroD'];
        $cod_prov = $validated['codProv'];
        $descrip_prov = $validated['provDescrip'];
        $bill_type = $validated['billType'];
        $tasa = $validated['tasa'];
        $is_dollar = $validated['isDollar'];
        $amount = $validated['amount'];
        $emission_date = $validated['fechaE'];
        $scheduleID = $validated['scheduleID'];

        $data = [
            'nro_doc' => $nro_doc,
            'cod_prov' => $cod_prov,
            'descrip_prov' => $descrip_prov,
            'bill_type' => $bill_type,
            'amount' => $amount,
            'is_dollar' => $is_dollar,
            'tasa' => $tasa,
            'emission_date' => $emission_date,
            'bill_payable_schedules_id' => $scheduleID
        ];
        
        BillPayable::upsert($data,
            ['nro_doc', 'cod_prov'],
            ['bill_payable_schedules_id']);
       
        return $this->jsonResponse($data, 200);
    }

    public function getProviders(Request $request, ProviderRepository $repo){
        $descrip = $request->query('descrip', '');

        $data = $repo->getProviders($descrip);


        return $this->jsonResponse($data, 200);
    }

    public function billPayableGroupsIndex(Request $request, BillsPayableRepository $repo,  BillSchedulesRepository $repo_schedule){
        $this->setSession($request, 'current_module', 'bill_payable');

        // Filter params
        $is_scheduled_bill = $request->query('is_scheduled_bill', 'yes');
        $cod_prov = $request->query('cod_prov', null);
        $descrip_prov =  $request->query('cod_prov_value', '');
        $status = $request->query('status', config("constants.BILL_PAYABLE_STATUS.NOTPAID")); 
        
        $statuses = array_map(function($item, $key){
            return (object) array("key" => $key, "value" => $item);
        }, config("constants.BILL_PAYABLE_STATUS"), array_keys(config("constants.BILL_PAYABLE_STATUS")));
       
        // Current page
        $page = $request->query('page', '');

        $paginator = null;

        $is_scheduled_bill_val = $is_scheduled_bill === 'yes' ? 1 : 0;

        $paginator = $repo->getBillPayableGroups($cod_prov)->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getBillPayableGroups($cod_prov)->paginate(5, ['*'], 'page', 1);
        }
 
        $data = array_map(function($item) {
            return (object) [
                'ID' => $item->ID,
                'CodProv' => $item->CodProv,
                'DescripProv' => $item->DescripProv,
                'Estatus' => config("constants.BILL_PAYABLE_STATUS." . $item->Estatus),
                'MontoTotal' => number_format($item->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS.dollar"),
                'MontoPagado' =>  $this->formatAmount($item->MontoPagado),
            ];
        }, $paginator->items());

        
    
        $schedules = $repo_schedule->getBillSchedules()->get()->map(function($item){
            return (object) array("key" => $item->WeekNumber, "value" => "Semana " . $item->WeekNumber);
        });

        $columns = [
            "Nro. Lote",
            "Proveedor",
            'Monto Factura ($)',
            'Monto Pagado ($)',
            'Estatus',
            'Opciones'
        ];

        return view('pages.bill-payable-groups.index', compact(
            'columns',
            'paginator',
            'data',
            'is_scheduled_bill',
            'cod_prov',
            'descrip_prov',
            'page',
            'schedules',
            'status',
            'statuses'
        ));
        
    }

    public function linkBillPayableGroupToSchedule(LinkBillPayableGroupToScheduleRequest $request){

        $validated = $request->validated();
        
        $bills_payable = BillPayableGroup::find($validated['groupID'])->bills_payable;

        foreach($bills_payable as $bill_payable){
            $bill_payable->bill_payable_schedules_id = $validated['scheduleID'];
            $bill_payable->save();
        }
       
        return $this->jsonResponse($bills_payable, 200);
    }
}
