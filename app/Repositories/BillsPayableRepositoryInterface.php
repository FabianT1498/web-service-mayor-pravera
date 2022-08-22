<?php

namespace App\Repositories;

interface BillsPayableRepositoryInterface {

	public function getBillPayable($cod_prov, $n_doc);
	public function getBillSPayable($ids);

	public function getBillPayablePaymentsBs($cod_prov, $n_doc);
	public function getBillPayablePaymentsDollar($n_doc, $cod_prov);
	
	public function getBillsPayableFromSaint($is_dolar, $before_emission_date, $bill_type, $nro_doc, $cod_prov);
	public function getBillPayableFromSaint($cod_prov, $n_doc, $bill_type);
}
