<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Repositories\BillsPayableRepository;
use App\Repositories\BillSchedulesRepository;

use App\Http\Requests\LinkBillPayableToScheduleRequest;
use App\Http\Requests\StoreBillPayablePaymentRequest;

use App\Models\BillPayable;
use App\Models\BillPayablePayment;

use App\Http\Traits\SessionTrait;

class BillsPayableController extends Controller
{
    private $flasher = null;

    use SessionTrait;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, BillsPayableRepository $repo, BillSchedulesRepository $repo_schedule){

        $this->setSession($request, 'current_module', 'bill_payable');

        $is_dollar = $request->query('is_dollar', 0);
        $end_emission_date = $request->query('end_emission_date', Carbon::now()->format('d-m-Y'));

        $min_available_days = $request->query('min_available_days', 0);
        $max_available_days = $request->query('max_available_days', 0);

        $is_caduced = $request->query('is_caduced', 1);

        $page = $request->query('page', '');

        $new_end_emission_date = '';

        $bill_types = array_map(function($val, $key){
            return (object) array("key" => $key, "value" => $val);
        }, config('constants.BILL_PAYABLE_TYPE'), array_keys(config('constants.BILL_PAYABLE_TYPE')));

        $bill_type = $request->query('bill_type', $bill_types[0]->key);

        if($end_emission_date === ''){
            $new_end_emission_date = Carbon::now()->format('Y-m-d');
        } else {
            $new_end_emission_date = date('Y-m-d', strtotime($end_emission_date));
        }

        $paginator = $repo->getBillsPayableFromSaint($is_dollar, $new_end_emission_date, $bill_type)->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getBillsPayableFromSaint($is_dollar, $new_end_emission_date, $bill_type)->paginate(5, ['*'], 'page', 1);
        }

        $bills_payable_keys = implode(" OR ", array_map(function($item){
            return "(bills_payable.cod_prov = '" . $item->CodProv . "' AND bills_payable.nro_doc = '" . $item->NumeroD . "')";
        }, $paginator->items()));

        $bills_payable_records = $repo->getBillsPayable($bills_payable_keys)->take(5)->get()->groupBy(['CodProv', 'NumeroD']);

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
                'DiasTranscurridos' => $days
            ];
        }, $paginator->items());

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
            'is_dollar',
            'bill_type',
            'bill_types',
            'end_emission_date',
            'min_available_days',
            'max_available_days',
            'is_caduced',
            'page',
            'schedules'
        ));
    }

    public function show(Request $request, BillsPayableRepository $repo){

        $columns = [
            "Fecha pago",
            'Banco',
            'Nro. referencia',
            'Es dolar',
            'Tasa',
            "Cantidad"
        ];

        $today_date = Carbon::now()->format('d-m-Y');
        
        $bill = $repo->getBillPayable($request->numero_d, $request->cod_prov);

        $bill_payments = $repo->getBillPayablePayments($request->numero_d, $request->cod_prov)->get();
        
        // return print_r($bill_payments);

        $banks = DB::connection('web_services_db')
            ->table('banks')
            ->select('name')
            ->get()
            ->map(function($item){
                return (object) array('key' => $item->name, 'value' => $item->name);
            });

        return view('pages.bills-payable.show', compact(
            'bill',
            'bill_payments',
            'banks',
            'today_date',
            'columns'
        ));
    }

    public function storePayment(StoreBillPayablePaymentRequest $request){

        $validated = $request->validated();

        $validated['cod_prov'] = $request->cod_prov;
        $validated['is_dollar'] = $request->isDollar === null ? '0' : '1';
        $validated['bank_name'] = $validated['bank'];
        $validated['ref_number'] = $validated['referenceNumber'];

        unset($validated['bank']);
        unset($validated['referenceNumber']);


        $result = BillPayablePayment::upsert($validated,
            ['nro_doc', 'cod_prov'],
            ['amount', 'bank_name', 'is_dollar', 'tasa', 'ref_number', 'date']);

        if ($result > 0){
            $this->flasher->addSuccess('El pago fue creado exitosamente!');
        } else {
            $this->flasher->addError('No se pudo crear el pago para la factura');
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

        $data = [
            'nro_doc' => $nro_doc,
            'cod_prov' => $cod_prov,
            'bill_type' => $bill_type,
            'amount' => $amount,
            'is_dollar' => $is_dollar,
            'tasa' => $tasa,
            'descrip_prov' => $descrip_prov,
            'bill_payable_schedules_id' => $schedule_id
        ];

        $bill = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$request->numeroD, $request->codProv])->first();

        if ($bill){
            $bill->bill_payable_schedules_id = $request->scheduleID;
            BillPayable::upsert($data,
                ['nro_doc', 'cod_prov'],
                ['bill_payable_schedules_id']);
        } else {
            BillPayable::insert($data);
        }
      
        return $this->jsonResponse($data, 200);
    }
}
