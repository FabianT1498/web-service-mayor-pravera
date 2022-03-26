<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointSaleBsRecord extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'point_sale_bs_records';
    public $timestamps = false;

    protected $fillable = [
        'amount',
        'type',
        'cash_register_data_id',
        'bank_name'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->amount = key_exists('amount', $attributes) ? $attributes['amount'] : '';
        $this->type =  key_exists('type', $attributes) ? $attributes['type'] : '';
        $this->cash_register_data_id = key_exists('cash_register_data_id', $attributes) ? $attributes['cash_register_data_id'] : '';
        $this->bank_name =  key_exists('bank_name', $attributes) ? $attributes['bank_name'] : '';
    }
}
