<?php

namespace App\Repositories;

interface BillsPayableRepositoryInterface {

	public function getBillPayable($cod_prov, $n_doc);
	public function getBillsPayableByIds($ids);
	public function getBillsPayable($is_dolar, $before_emission_date, $bill_type, $nro_doc, $cod_prov, $is_scheduled_bill);
	public function getBillsPayableByScheduleId($is_dollar, $bill_payable_schedules_id);

	public function getBillPayablePaymentsCount($n_doc, $cod_prov);
	public function getBillPayablePaymentsBs($cod_prov, $n_doc);
	public function getBillPayablePaymentsDollar($n_doc, $cod_prov);
	
	public function getBillsPayableFromSaint($is_dolar, $before_emission_date, $bill_type, $nro_doc, $cod_prov);
	public function getBillPayableFromSaint($cod_prov, $n_doc, $bill_type);
}
