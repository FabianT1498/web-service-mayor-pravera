<?php 

namespace App\Repositories;

use App\Models\DollarExchange;

class DollarExchangeRepository implements DollarExchangeRepositoryInterface
{
	public function getLast(){
		return DollarExchange::orderBy('created_at', 'desc')->first();
	}
}