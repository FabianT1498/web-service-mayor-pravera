<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Repositories\BillsPayableRepository;
use App\Repositories\BillSchedulesRepository;

use App\Models\BillPayableSchedule;

use App\Http\Traits\SessionTrait;

class SchedulePayableController extends Controller
{
    private $flasher = null;

    use SessionTrait;

    public function __construct(SweetAlertFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    public function index(Request $request, BillSchedulesRepository $repo){

        $this->setSession($request, 'current_module', 'bill_payable');

        $start_date = $request->query('start_date', '');
        $end_date = $request->query('end_date', '');

        $page = $request->query('page', '');

        $new_start_date = '';
        $new_end_date = '';

        $schedule_statuses = array_map(function($val, $key){
            return (object) array("key" => $key, "value" => $val);
        }, config('constants.BILL_PAYABLE_SCHEDULE_STATUS'), array_keys(config('constants.BILL_PAYABLE_SCHEDULE_STATUS')));

        $status = $request->query('status', $schedule_statuses[1]->key);

        if($start_date !== '' || $end_date !== ''){
            $new_start_date = date('Y-m-d', strtotime($start_date));
            $new_end_date = date('Y-m-d', strtotime($end_date));
        }

        $paginator = $repo->getBillSchedules($new_start_date, $new_end_date, $status)->paginate(5);

        if ($paginator->lastPage() < $page){
            $paginator = $repo->getBillSchedules($new_start_date, $new_end_date, $status)->paginate(5, ['*'], 'page', 1);
        }

        $columns = [
            "Semana",
            "F. Inicio",
            "F. Final",
            "Estatus",
            'Cant. facturas',
            "Opciones"
        ];

        return view('pages.bill-payable-schedules.index', compact(
            'columns',
            'paginator',
            'start_date',
            'end_date',
            'schedule_statuses',
            'status',
            'page',
        ));
    }

    public function create(Request $request)
    {

        $this->setSession($request, 'current_module', 'bill_payable');

        $today_date = Carbon::now()->format('d-m-Y');

        $data = compact(
            'today_date'
        );

        return view('pages.bill-payable-schedules.create', $data);
    }

    public function store(Request $request)
    {
        $data = [
            'start_date' => '',
            'end_date' => '',
        ];

        if (BillPayableSchedule::count() === 0){

            $data['start_date'] = Carbon::now()->startOfWeek()->format('Y-m-d');
            $data['end_date'] = Carbon::now()->endOfWeek()->format('Y-m-d');
        } else {
            $schedule = BillPayableSchedule::orderBy('id', 'desc')->first();

            $target_date = CarbonImmutable::createFromFormat('Y-m-d', $schedule->end_date)->addDay();
        
            $start_date = Carbon::now()->startOfWeek();

            if ($target_date < $start_date){
                $target_date = $start_date;   
            }

            $end_date_week = $target_date->endOfWeek();

            $data['start_date'] = $target_date->format('Y-m-d');
            $data['end_date'] = $end_date_week->format('Y-m-d');

            
        }
        
        $schedule = BillPayableSchedule::create($data);

        if ($schedule->save()){
            $this->flasher->addSuccess('La programación fue creada exitosamente!');
        } else {
            $this->flasher->addError('No se pudo crear la programación');
        }
        
        return redirect()->route('schedule.index');
    }

    public function show(Request $request, $id, BillsPayableRepository $repo)
    {

        $this->setSession($request, 'current_module', 'bill_payable');

        $bill_payable_schedule = BillPayableSchedule::find($id);

        $dollar_bill_payable = $bs_bill_payable = null;

        if ($bill_payable_schedule){
            $dollar_bill_payable = $repo->getBillsPayableByScheduleId($id, 1)->get();

            $bs_bill_payable = $repo->getBillsPayableByScheduleId($id, 0)->get();

            $groups = $repo->getBillPayableGroupsByScheduleID($id);

            $dollar_bill_payable_grouped = $repo->getBillsPayableByScheduleId($id, 1, true)->get()->groupBy(['BillPayableGroupsID']);
            $bs_bill_payable_grouped = $repo->getBillsPayableByScheduleId($id, 0, true)->get()->groupBy(['BillPayableGroupsID']);

            $are_all_bills_paid = true;

            // Verificar si todas las facturas han sido pagadas, solo si la programación está en proceso
            if (config("constants.BILL_PAYABLE_SCHEDULE_STATUS." . $bill_payable_schedule->status) === config("constants.BILL_PAYABLE_SCHEDULE_STATUS.PROCESSING")){
                $dollar_bill_payable->each(function($item) use (&$are_all_bills_paid) {
                    if (config("constants.BILL_PAYABLE_STATUS." . $item->Status) === config("constants.BILL_PAYABLE_STATUS.NOTPAID")){
                        $are_all_bills_paid = false;
                        return false;
                    }
                });
    
                if ($are_all_bills_paid){
                    $bs_bill_payable->each(function($item) use (&$are_all_bills_paid) {
                        if (config("constants.BILL_PAYABLE_STATUS." . $item->Status) === config("constants.BILL_PAYABLE_STATUS.NOTPAID")){
                            $are_all_bills_paid = false;
                            return false;
                        }
                    });
                }
            }
        }

        // return print_r($are_all_bills_paid ? "Todas las facturas han sido pagadas" : "Hay facturas sin pagar");

        $columns = [
            "Numero Documento",
            "Proveedor",
            "Monto total",
            "Tasa",
            "Monto pagar",
            "Monto pagado",
            "Estatus"
        ];

        return view('pages.bill-payable-schedules.show', compact(
            'columns',
            'bill_payable_schedule',
            'dollar_bill_payable',
            'bs_bill_payable',
            'groups',
            'dollar_bill_payable_grouped',
            'bs_bill_payable_grouped'
        ));
    }

    // Function to get processing schedules
    public function getBillPayableSchedules(Request $request, BillSchedulesRepository $repo){
        $schedules = $repo->getBillSchedules()->get();
        return $this->jsonResponse(['data' => $schedules], 200);
    }

    public function getSchedule($id, BillSchedulesRepository $repo){
        $schedule = $repo->getBillSchedule($id)->first();
        return $this->jsonResponse($schedule, 200);
    }
}
