<?php

namespace App\Repositories;

interface BillRepositoryInterface {
	public function getVueltos($start_date, $end_date);
	public function getVueltosByUser($start_date, $end_date, $user = null);
}