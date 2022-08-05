<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;


class BillPayable extends Model
{
   
    protected $connection = 'web_services_db';
    protected $table = 'bills_payable';
    public $timestamps = false;

    protected $fillable = [
        'nro_doc',
        'cod_prov',
        'bill_type',
        'amount',
        'tasa',
        'is_dollar',
        'status'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->nro_doc = key_exists('nro_doc', $attributes) ? $attributes['nro_doc'] : '';
        $this->cod_prov =  key_exists('cod_prov', $attributes) ? $attributes['cod_prov'] : '';
        $this->bill_type = key_exists('bill_type', $attributes) ? $attributes['bill_type'] : '';
        $this->amount = key_exists('amount', $attributes) ? $attributes['amount'] : '';
        $this->is_dollar = key_exists('is_dollar', $attributes) ? $attributes['is_dollar'] : '';
        $this->status = key_exists('status', $attributes) ? $attributes['status'] : array_keys(config('constants.BILL_PAYABLE_STATUS'))[0];
        $this->tasa = key_exists('tasa', $attributes) ? $attributes['tasa'] : 0;
        $this->bill_payable_schedules_id = key_exists('bill_payable_schedules_id', $attributes) ? $attributes['bill_payable_schedules_id'] : null;
    }

    // public function dollar_cash_records()
    // {
    //     return $this->hasMany(DollarCashRecord::class);
    // }

    // public function pago_movil_bs_records()
    // {
    //     return $this->hasMany(PagoMovilRecord::class);
    // }  
}
