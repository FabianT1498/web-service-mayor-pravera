<?php

namespace App\Repositories;

interface CashRegisterRepositoryInterface {

	public function getTotalsFromSafact($start_date, $end_date, $user);

  public function getTotalsEPaymentMethods($start_date, $end_date, $user);
	// more
}
