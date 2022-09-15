<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BillsPayablePayments extends Model
{
   
    protected $connection = 'web_services_db';
    protected $table = 'bills_payable_payments';
    public $timestamps = false;

    protected $fillable = [
        'nro_doc',
        'cod_prov',
        'bill_payments_id',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->nro_doc = key_exists('nro_doc', $attributes) ? $attributes['nro_doc'] : '';
        $this->cod_prov =  key_exists('cod_prov', $attributes) ? $attributes['cod_prov'] : '';
        $this->bill_payments_id = key_exists('bill_payments_id', $attributes) ? $attributes['bill_payments_id'] : '';
    }
}
