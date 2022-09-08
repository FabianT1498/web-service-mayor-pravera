<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BillPayablePayment extends Model
{
   
    protected $connection = 'web_services_db';
    protected $table = 'bill_payments';
    public $timestamps = false;

    protected $fillable = [
        'amount',
        'date',
        'is_dollar',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->amount = key_exists('amount', $attributes) ? $attributes['amount'] : '';
        $this->date = key_exists('date', $attributes) ? $attributes['date'] : Carbon::now()->format('Y-m-d');
        $this->is_dollar = key_exists('is_dollar', $attributes) ? $attributes['is_dollar'] : '';
        $this->is_group_payment = key_exists('is_group_payment', $attributes) ? $attributes['is_group_payment'] : '';
    }
}
