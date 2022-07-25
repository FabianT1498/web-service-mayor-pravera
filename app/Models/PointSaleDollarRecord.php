<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointSaleDollarRecord extends Model
{
    use HasFactory;

    protected $connection = 'web_services_db';
    protected $table = 'point_sale_dollar_records';
    public $timestamps = false;

    protected $fillable = [
        'amount',
        'point_sale_user',
        'cash_register_data_id',
        'bank_name'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->amount = key_exists('amount', $attributes) ? $attributes['amount'] : '';
        $this->cash_register_data_id = key_exists('cash_register_data_id', $attributes) ? $attributes['cash_register_data_id'] : '';
    }
}
