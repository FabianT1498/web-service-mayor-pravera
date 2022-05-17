<?php

namespace App\Repositories;

interface BillRepositoryInterface {

	public function getValesAndVueltos($start_date, $end_date);

}