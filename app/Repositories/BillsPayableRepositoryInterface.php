<?php

namespace App\Repositories;

interface BillsPayableRepositoryInterface {

	public function getBillPayable($cod_prov, $n_doc);
	public function getBillSPayable($ids);
	public function getBillPayablePayments($cod_prov, $n_doc);
	
	public function getBillsPayableFromSaint($is_dolar, $before_emission_date, $bill_type);
	public function getBillPayableFromSaint($cod_prov, $n_doc, $bill_type);
}
