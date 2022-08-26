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
use App\Http\Requests\StoreBillPayablePaymentRequest;
use App\Http\Requests\UpdateBillPayableTasaRequest;
use App\Models\BillPayable;
use App\Models\BillPayablePayment;
use App\Models\BillPayablePaymentBs;
use App\Models\BillPayablePaymentDollar;

use App\Http\Traits\SessionTrait;
use App\Http\Traits\AmountCurrencyTrait;

class BillsPayableController extends Controller
{
    private $flasher = null;

    use SessionTrait;
    use AmountCurrencyTrait;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, BillsPayableRepository $repo, BillSchedulesRepository $repo_schedule){

        $this->setSession($request, 'current_module', 'bill_payable');

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

        if ($is_scheduled_bill === 'yes'){
            $paginator = $repo->getBillsPayable($is_dollar, $new_end_emission_date, $bill_type, $nro_doc, $cod_prov)->paginate(5);

            if ($paginator->lastPage() < $page){
                $paginator = $repo->getBillsPayable($is_dollar, $new_end_emission_date, $bill_type, $nro_doc, $cod_prov)->paginate(5, ['*'], 'page', 1);
            }

            $data = array_map(function($item){
                
                $datetime1 = new \DateTime();
                $datetime2 = new \DateTime($item->FechaE);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');//now do whatever you like with $days

                return (object) [
                    'NumeroD' => $item->NumeroD,
                    'CodProv' => $item->CodProv,
                    'Descrip' => $item->Descrip,
                    'FechaE' => $item->FechaE,
                    'FechaPosteo' => $item->FechaE,
                    'esDolar' => $item->esDolar,
                    'MontoTotal' => number_format($item->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS." . ($item->esDolar ? "dollar" : "bolivar")),
                    'MontoPagar' => number_format($item->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS." . ($item->esDolar ? "dollar" : "bolivar")),
                    'Tasa' => number_format($item->Tasa, 2),
                    'Estatus' => isset($item->Status)  ? config("constants.BILL_PAYABLE_STATUS." . $item->Status) : config("constants.BILL_PAYABLE_STATUS.NOTPAID"),
                    'DiasTranscurridos' => $days,
                    'BillPayableSchedulesID' => isset($item->BillPayableSchedulesID) ? $item->BillPayableSchedulesID : null
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

            $bills_payable_records = $repo->getBillsPayableByIds($bills_payable_keys)->take(5)->get()->groupBy(['CodProv', 'NumeroD']);

            $data = array_map(function($item) use ($bills_payable_records){
                
                $record = $bills_payable_records->has($item->CodProv) && $bills_payable_records[$item->CodProv]->has($item->NumeroD)
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
                    'Tasa' => number_format($record->Tasa, 2),
                    'Estatus' => isset($record->Status)  ? config("constants.BILL_PAYABLE_STATUS." . $record->Status) : config("constants.BILL_PAYABLE_STATUS.NOTPAID"),
                    'DiasTranscurridos' => $days,
                    'BillPayableSchedulesID' => isset($record->BillPayableSchedulesID) ? $record->BillPayableSchedulesID : null
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
            'Monto Pagar',
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
            'bill_currency'
        ));
    }

    public function show(Request $request, BillsPayableRepository $repo){


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

        $today_date = Carbon::now()->format('d-m-Y');
        
        $bill = $repo->getBillPayable($request->numero_d, $request->cod_prov);
      
        $bill->Tasa = $this->formatAmount($bill->Tasa);

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
        ));
    }

    public function storePayment(StoreBillPayablePaymentRequest $request, BillsPayableRepository $repo){

        $validated = $request->validated();

        $validated['is_dollar'] = key_exists('is_dollar', $request->all()) ? $request->all()['is_dollar'] : '0';

        $bill_payment = BillPayablePayment::create($validated);

        if ($bill_payment){

            $bill_payment_child = null;

            $validated['bill_payments_id'] = $bill_payment->id;

            $bill = $repo->getBillPayable($validated['nro_doc'], $validated['cod_prov']);

            $bill_amount_to_pay_ref = $bill->MontoPagar;
            $payment_amount_ref = 0;

            if ($validated['is_dollar'] === '0'){
                $payment_amount_ref = $validated['amount'] / $validated['tasa'];
                $bill_payment_child = BillPayablePaymentBs::create($validated);
               
            } else if ($validated['is_dollar'] === '1') {
                $payment_amount_ref = $validated['amount'];
                $validated['payment_method'] = $validated['foreign_currency_payment_method'];
                unset($validated['foreign_currency_payment_method']);
                $bill_payment_child = BillPayablePaymentDollar::create($validated);
            }

            $diff = $bill_amount_to_pay_ref - $payment_amount_ref;

            $bill_status_change = false;

            if ($diff <= 0){
                $bill_model = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$validated['nro_doc'], $validated['cod_prov']])->first();
                $bill_model->status = array_keys(config("constants.BILL_PAYABLE_STATUS"))[1];
                $bill_status_change = $bill_model->save();
            }

            if ($bill_payment_child){
                $this->flasher->addSuccess("El pago fue creado exitosamente " . ($bill_status_change ? "y la factura fue pagada completamente !" : "!"));
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

    public function storeBillPayable(Request $request){

        $nro_doc = $request->numeroD;
        $cod_prov = $request->codProv;
        $descrip_prov = $request->provDescrip;
        $bill_type = $request->billType;
        $tasa = $request->tasa;
        $is_dollar = $request->isDollar;
        $amount = $request->amount;
      
        $data = [
            'nro_doc' => $nro_doc,
            'cod_prov' => $cod_prov,
            'descrip_prov' => $descrip_prov,
            'bill_type' => $bill_type,
            'amount' => $amount,
            'is_dollar' => $is_dollar,
            'tasa' => $tasa,
        ];

        BillPayable::upsert($data,
            ['nro_doc', 'cod_prov'],
            ['amount', 'is_dollar', 'tasa', 'bill_type']);

        return $this->jsonResponse($data, 200);
    }

    public function getBillPayable(Request $request, BillsPayableRepository $repo){
        $bill = $repo->getBillPayable($request->numero_d, $request->cod_prov);

        return $this->jsonResponse($bill ? [$bill] : [], 200);
    }

    public function linkBillPayableToSchedule(LinkBillPayableToScheduleRequest $request, BillsPayableRepository $repo){

        $nro_doc = $request->numeroD;
        $cod_prov = $request->codProv;
        $bill_type = $request->billType;
        $tasa = $request->tasa;
        $is_dollar = $request->isDollar;
        $amount = $request->amount;
        $schedule_id = $request->scheduleID;
        $descrip_prov = $request->provDescrip;
        $emission_date = $request->fechaE;

        $data = [
            'nro_doc' => $nro_doc,
            'cod_prov' => $cod_prov,
            'bill_type' => $bill_type,
            'amount' => $amount,
            'is_dollar' => $is_dollar,
            'tasa' => $tasa,
            'descrip_prov' => $descrip_prov,
            'emission_date' =>  Carbon::createFromFormat('d-m-Y', $emission_date)->format('Y-m-d'),
            'bill_payable_schedules_id' => $schedule_id
        ];

        $bill = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$request->numeroD, $request->codProv])->first();

        if ($bill){
            if (config("constants.BILL_PAYABLE_STATUS." . $bill->status) === config("constants.BILL_PAYABLE_STATUS.PAID")){
                return $this->jsonResponse(['error' => 400, 'message' => 'Ya esta factura fue pagada', 'data' => null], 400);
            }

            $bill->bill_payable_schedules_id = $request->scheduleID;
            BillPayable::upsert($data,
                ['nro_doc', 'cod_prov'],
                ['bill_payable_schedules_id']);
        } else {
            BillPayable::insert($data);
        }
      
        return $this->jsonResponse($data, 200);
    }

    public function getProviders(Request $request, ProviderRepository $repo){
        $descrip = $request->query('descrip', '');

        $data = $repo->getProviders($descrip);


        return $this->jsonResponse($data, 200);

    }
}
