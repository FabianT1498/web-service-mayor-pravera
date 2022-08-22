<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BillPayablePaymentDollar extends Model
{
   
    protected $connection = 'web_services_db';
    protected $table = 'bill_payments_dollar';
    public $timestamps = false;

    protected $fillable = [
        'payment_method',
        'retirement_date',
        'bill_payments_id'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
        $this->bill_payments_id = key_exists('bill_payments_id', $attributes) ? $attributes['bill_payments_id'] : '';
        $this->payment_method = key_exists('payment_method', $attributes) ? $attributes['payment_method'] : '';
        $this->retirement_date = key_exists('retirement_date', $attributes) ? $attributes['retirement_date'] : Carbon::now()->format('Y-m-d');
    }
}
