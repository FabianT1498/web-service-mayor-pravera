<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BillPayableGroup extends Model
{
   
    protected $connection = 'web_services_db';
    protected $table = 'bill_payable_groups';
    public $timestamps = false;

    protected $fillable = [
        'cod_prov',
        'status',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->cod_prov = key_exists('cod_prov', $attributes) ? $attributes['cod_prov'] : '';
        $this->status = key_exists('status', $attributes) ? $attributes['status'] : array_keys(config('constants.BILL_PAYABLE_STATUS'))[0];
    }
}
