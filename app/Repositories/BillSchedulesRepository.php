<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillSchedulesRepository implements BillSchedulesRepositoryInterface
{

    // Metodo para obtener las facturas por pagar
    public function getBillSchedules($start_date = '', $end_date = '', $status = ''){

        if ($status === ''){
            $status = array_keys(config('constants.BILL_PAYABLE_SCHEDULE_STATUS'))[1];
        }

        $interval_query = '';
        $where_params = [];

        if($start_date !== ''  && $end_date !== ''){
            $interval_query = "bill_payable_schedules.start_date >= ? AND bill_payable_schedules.end_date <= ?";
            $where_params = [$start_date, $end_date];
        } else if($start_date !== ''){
            $interval_query = "bill_payable_schedules.start_date => ?";
            $where_params = [$start_date];
        } else if($end_date !== ''){
            $interval_query = "bill_payable_schedules.end_date <= ?";
            $where_params = [$end_date];
        }

        $where_params[] = $status;

        return DB
            ::connection('web_services_db')
            ->table('bill_payable_schedules')
            ->selectRaw("bill_payable_schedules.id as WeekNumber, bill_payable_schedules.start_date as StartDate, bill_payable_schedules.end_date as EndDate, bill_payable_schedules.status as Status,
                COALESCE(scheduled_bills.count, 0) as QtyBillsScheduled")
            ->leftJoin(DB::raw('(SELECT COUNT(bills_payable.nro_doc) AS count, bills_payable.bill_payable_schedules_id AS bill_payable_schedules_id 
                    FROM bills_payable WHERE bills_payable.bill_payable_schedules_id IS NOT NULL GROUP BY bills_payable.bill_payable_schedules_id) AS scheduled_bills'),
                 function($join){
                    $join->on('scheduled_bills.bill_payable_schedules_id', '=', 'bill_payable_schedules.id');
                })
            ->whereRaw(($interval_query !== '' ?  $interval_query . " AND " : "") . "bill_payable_schedules.status = ?", $where_params)
            ->orderByRaw("bill_payable_schedules.start_date ASC");  
    }

    public function getBillSchedule($id, $status = ''){

        if ($status === ''){
            $status = array_keys(config('constants.BILL_PAYABLE_SCHEDULE_STATUS'))[1];
        }

        $where_params = [$id, $status];

        return DB
        ::connection('web_services_db')
        ->table('bill_payable_schedules')
        ->selectRaw("bill_payable_schedules.id as WeekNumber, bill_payable_schedules.start_date as StartDate, bill_payable_schedules.end_date as EndDate, bill_payable_schedules.status as Status,
                COALESCE(scheduled_bills.count, 0) as QtyBillsScheduled")
        ->leftJoin(DB::raw('(SELECT COUNT(bills_payable.nro_doc) AS count, bills_payable.bill_payable_schedules_id AS bill_payable_schedules_id 
                FROM bills_payable WHERE bills_payable.bill_payable_schedules_id IS NOT NULL GROUP BY bills_payable.bill_payable_schedules_id) AS scheduled_bills'),
                function($join){
                $join->on('scheduled_bills.bill_payable_schedules_id', '=', 'bill_payable_schedules.id');
            })
        ->whereRaw("bill_payable_schedules.id = ? AND bill_payable_schedules.status = ?", $where_params);
    }
}
