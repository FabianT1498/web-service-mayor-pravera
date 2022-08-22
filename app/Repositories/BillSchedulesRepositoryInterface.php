<?php

namespace App\Repositories;

interface BillSchedulesRepositoryInterface {

	public function getBillSchedules($start_date, $end_date, $status);

	public function getBillSchedule($id);
}