<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Repositories\BillsPayableRepository;

use App\Models\BillPayable;

// use App\Http\Traits\SessionTrait;

class BillsPayableController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, BillsPayableRepository $repo){

        $is_dolar = $request->query('is_dolar', 0);
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

        $paginator = $repo->getBillsPayable($is_dolar, $new_end_emission_date, $bill_type)->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getBillsPayable($is_dolar, $new_end_emission_date, $bill_type)->paginate(5, ['*'], 'page', 1);
        }

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
            "Opciones"
        ];

        return view('pages.bills-payable.index', compact(
            'columns',
            'paginator',
            'is_dolar',
            'bill_type',
            'bill_types',
            'end_emission_date',
            'min_available_days',
            'max_available_days',
            'is_caduced',
            'page',
        ));
    }

    public function getBillPayable(Request $request, BillsPayableRepository $repo){

    }

    public function storeBillPayable(Request $request, BillsPayableRepository $repo){

        // 1. Consultar si la factura esta almacenada en la base de datos
        $nro_doc = $request->numeroD;
        $cod_prov = $request->codProv;
        $bill_type = $request->billType;
        $tasa = $request->tasa;
        $is_dollar = $request->isDollar;
        $amount = $request->amount;
    
        if ($is_dollar){
            $amount = $amount / $tasa;
        } else {
            $amount = $amount * $tasa;
        }
        
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

            return $this->jsonResponse(['data' => [
                'status' => array_keys(config('constants.BILL_PAYABLE_STATUS'))[0] 
            ]], 200);
    }
}
