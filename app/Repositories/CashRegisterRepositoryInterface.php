<?php

namespace App\Repositories;

interface CashRegisterRepositoryInterface {

	public function getTotalsFromSafact($start_date, $end_date, $user);

  	public function getTotalsEPaymentMethods($start_date, $end_date, $user);

	public function getTotals($id);

	public function getTotalsByInterval($start_date, $end_date);

	public function getZelleRecords($start_date, $end_date);

	public function getFactorByDate($start_date, $end_date);

	public function getZelleRecordsFromSaint($start_date, $end_date, $user);

	public function getZelleTotalByUserFromSaint($start_date, $end_date, $user);

	public function getZelleTotalFromSaint($start_date, $end_date);
	// more
}
