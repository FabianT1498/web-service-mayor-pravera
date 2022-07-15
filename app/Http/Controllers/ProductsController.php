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

use App\Repositories\ProductsRepository;


use App\Models\ProductsNotes;

class ProductsController extends Controller
{
    private $flasher = null;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, ProductsRepository $repo){

        $descrip = $request->query('description', '');
        $instance = $request->query('product_instance', 1652);
        $is_active = $request->query('is_active', 1);
        $page = $request->query('page', '');

        $paginator_params = [];
        
        $instances = $repo->getInstances()->map(function($item, $key) {
            return (object) array("key" => $item->CodInst, "value" => $item->Descrip);
        });

        $paginator = $repo->getProducts($descrip, $is_active, $instance, $paginator_params)->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getProducts($descrip, $is_active, $instance, $paginator_params)->paginate(5, ['*'], 'page', 1);
        }

        $columns = [
            "Cod. Produc",
            "Descrip.",
            "Costo",
            'Precio venta con IVA($)',
            'IVA',
            "% de ganancia",
        ];

        return view('pages.products.index', compact(
            'columns',
            'paginator',
            'descrip',
            'instance',
            'is_active',
            'instances',
            'page',
        ));
    }

    // public function create()
    // {

    //     $cash_registers_workers_id_arr = $this->getWorkers();

    //     $today_date = Carbon::now();
    //     $cash_registers_id_arr = $this
    //         ->getCashRegisterUsersWithoutRecord($today_date->format('Y-m-d'));

    //     if ($cash_registers_id_arr->count() === 0){
    //         $this->flasher->addInfo('Ya se han registrado arqueos de caja para todas las cajas el dia de hoy,
    //             por favor seleccione otra fecha');
    //     }

    //     $today_date = $today_date->format('d-m-Y');

    //     $data = compact(
    //         'cash_registers_id_arr',
    //         'cash_registers_workers_id_arr',
    //         'today_date'
    //     );

    //     return view('pages.cash-register.create', $data);
    // }

    // public function store(StoreCashRegisterRequest $request)
    // {
    //     $validated = $request->validated();

    //     $validated += ["user_id" => Auth::user()->CodUsua];

    //     if (array_key_exists('new_cash_register_worker', $validated)){
    //         $worker = new Worker(array('name' => $validated['new_cash_register_worker']));
    //         $worker->save();
    //         $validated = array_merge($validated, array('worker_id' => $worker->id));
    //     }

    //     $cash_register_data = new CashRegisterData($validated);

    //     if ($cash_register_data->save()){
    //         if (array_key_exists('dollar_cash_record', $validated)){
    //             $data = array_reduce($validated['dollar_cash_record'], function($acc, $value) use ($cash_register_data){
    //                 if ($value > 0){
    //                     $acc[] = array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
    //                 }

    //                 return $acc;
    //             }, []);
    //             DollarCashRecord::insert($data);
    //         }

    //         if (array_key_exists('pago_movil_record', $validated)){
    //             $data = array_reduce($validated['pago_movil_record'], function($acc, $value) use ($cash_register_data){
    //                 if ($value > 0){
    //                     $acc[] = array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
    //                 }

    //                 return $acc;
    //             }, []);
    //             PagoMovilRecord::insert($data);
    //         }

    //         if (array_key_exists('dollar_denominations_record', $validated)){
    //             $data = array_map(function($quantity, $denomination) use ($cash_register_data){
    //                 return array(
    //                     'quantity' => $quantity,
    //                     'denomination' => floatval($denomination . 'El'),
    //                     'cash_register_data_id' => $cash_register_data->id
    //                 );
    //             }, $validated['dollar_denominations_record'], array_keys($validated['dollar_denominations_record']));
    //             DollarDenominationRecord::insert($data);
    //         }

    //         if (array_key_exists('bs_denominations_record', $validated)){
    //             $data = array_map(function($quantity, $denomination) use ($cash_register_data){
    //                 return array(
    //                     'quantity' => $quantity,
    //                     'denomination' => floatval($denomination . 'El'),
    //                     'cash_register_data_id' => $cash_register_data->id
    //                 );
    //             }, $validated['bs_denominations_record'], array_keys($validated['bs_denominations_record']));
    //             BsDenominationRecord::insert($data);
    //         }

    //         if (array_key_exists('point_sale_bs', $validated)){
                
    //             foreach($validated['point_sale_bs'] as &$record){
    //                 $record['cash_register_data_id'] = $cash_register_data->id;
    //             }
                
    //             PointSaleBsRecord::insert($validated['point_sale_bs']);
    //         }

    //         if (array_key_exists('notes', $validated)){
                
    //             $data = array_reduce($validated['notes'], function($acc, $note) use ($cash_register_data){
    //                 if 
    //                 (!is_null($note['description']) && $note['description'] !== ''){
    //                     $acc[] = array(
    //                         'title' => $note['title'],
    //                         'description' => $note['description'],
    //                         'cash_register_data_id' => $cash_register_data->id);
    //                 }

    //                 return $acc;
    //             }, []);
                
    //             Note::insert($data);
    //         }

    //         if (array_key_exists('total_point_sale_dollar', $validated) 
    //             && $validated['total_point_sale_dollar'] > 0){
    //             $data = [
    //                 'amount' => $validated['total_point_sale_dollar'],
    //                 'cash_register_data_id' => $cash_register_data->id
    //             ];
    //             PointSaleDollarRecord::insert($data);
    //         }

    //         if (array_key_exists('zelle_record', $validated)){
    //             $data = array_reduce($validated['zelle_record'], function($acc, $value) use ($cash_register_data){
    //                 if ($value > 0){
    //                     $acc[] = array('amount' => $value, 'cash_register_data_id' => $cash_register_data->id);
    //                 }

    //                 return $acc;
    //             }, []);
    //             ZelleRecord::insert($data);
    //         }

    //         $this->flasher->addSuccess('El arqueo de caja se guardó exitosamente!');
    //     } else {
    //         $this->flasher->addError('El arqueo de caja no se pudo guardar');
    //     }

    //     return redirect()->route('cash_register.index');
    // }
}
