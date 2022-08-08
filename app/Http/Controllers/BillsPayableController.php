<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Repositories\BillsPayableRepository;

use App\Models\BillPayable;
use PhpParser\Node\Stmt\Return_;

use App\Http\Traits\SessionTrait;

class BillsPayableController extends Controller
{
    private $flasher = null;

    use SessionTrait;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, BillsPayableRepository $repo){

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
        ));
    }

    public function storeBillPayable(Request $request){

        $nro_doc = $request->numeroD;
        $cod_prov = $request->codProv;
        $bill_type = $request->billType;
        $tasa = $request->tasa;
        $is_dollar = $request->isDollar;
        $amount = $request->amount;
      
        $data = [
            'nro_doc' => $nro_doc,
            'cod_prov' => $cod_prov,
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
}
