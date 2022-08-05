<?php

namespace App\Repositories;

interface BillsPayableRepositoryInterface {

	public function getBillsPayable($is_dolar, $before_emission_date, $bill_type);

	public function getBillPayable($cod_prov, $n_doc);

	public function getBillPayableFromSaint($cod_prov, $n_doc, $bill_type);
}
