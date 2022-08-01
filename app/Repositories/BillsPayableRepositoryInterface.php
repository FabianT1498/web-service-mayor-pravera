<?php

namespace App\Repositories;

interface BillsPayableRepositoryInterface {

	public function getBillsPayable($is_dolar, $before_emission_date, $bill_type);

}
