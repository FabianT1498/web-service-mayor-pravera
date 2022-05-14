<?php

namespace App\Repositories;

interface ZBillRepositoryInterface {

	public function getTotalsFromSafact($start_date, $end_date);

  	public function getTotalLicores($start_date, $end_date);

	public function getBaseImponibleByTax($start_date, $end_date);

	public function getAmountBills($start_date, $end_date);

	public function getZNumbersByPrinter($start_date, $end_date);
}