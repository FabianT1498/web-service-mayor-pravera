<?php 

namespace App\Repositories;

interface DollarExchangeRepositoryInterface {
	
	public function getLast();

	public function getLastToDate($date);

	// more
}