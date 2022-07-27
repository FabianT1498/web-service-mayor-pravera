<?php

namespace App\Repositories;

interface BillsPayableRepositoryInterface {

	public function getBillsPayable($is_dolar, $start_emision_date, $end_emision_date, $min_remaining_days, $max_remaining_days, $is_caduced);

}
