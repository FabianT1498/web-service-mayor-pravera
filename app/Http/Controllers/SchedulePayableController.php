<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

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

    
}
