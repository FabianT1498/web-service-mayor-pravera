<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMovilRecord extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'pago_movil_bs_records';

    public $timestamps = false;

    protected $fillable = [
        'amount',
        'cash_register_data_id',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->amount = key_exists('amount', $attributes) ? $attributes['amount'] : '';
        $this->cash_register_data_id =  key_exists('cash_register_data_id', $attributes) ? $attributes['cash_register_data_id'] : '';
    }
}
