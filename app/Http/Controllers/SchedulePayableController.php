<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Flasher\SweetAlert\Prime\SweetAlertFactory;

use App\Repositories\BillSchedulesRepository;

use App\Models\BillPayable;

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
}
