<?php

namespace App\Repositories;

interface BillsPayableRepositoryInterface {

	public function getBillsPayable($is_dolar, $start_emision_date, $end_emision_date);

}
