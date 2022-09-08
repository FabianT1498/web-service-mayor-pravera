<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BillPayablePaymentBs extends Model
{
   
    protected $connection = 'web_services_db';
    protected $table = 'bill_payments_bs';
    public $timestamps = false;

    protected $fillable = [
        'bank_name',
        'ref_number',
        'tasa',
        'bill_payments_id'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
 
        $this->bill_payments_id = key_exists('bill_payments_id', $attributes) ? $attributes['bill_payments_id'] : '';
        $this->bank_name =  key_exists('bank_name', $attributes) ? $attributes['bank_name'] : '';
        $this->ref_number = key_exists('ref_number', $attributes) ? $attributes['ref_number'] : '';
        $this->tasa = key_exists('tasa', $attributes) ? $attributes['tasa'] : 0;
    }
}
