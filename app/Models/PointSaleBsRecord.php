<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointSaleBsRecord extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'point_sale_bs_records_2';
    public $timestamps = false;

    protected $fillable = [
        'cancel_amex',
        'cancel_todoticket',
        'cancel_debit',
        'cancel_credit',
        'cash_register_data_id',
        'bank_name'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->cancel_amex = key_exists('cancel_amex', $attributes) ? $attributes['cancel_amex'] : '';
        $this->cancel_todoticket = key_exists('cancel_todoticket', $attributes) ? $attributes['cancel_todoticket'] : '';
        $this->cancel_debit = key_exists('cancel_debit', $attributes) ? $attributes['cancel_debit'] : '';
        $this->cancel_credit = key_exists('cancel_credit', $attributes) ? $attributes['cancel_credit'] : '';
        $this->cash_register_data_id = key_exists('cash_register_data_id', $attributes) ? $attributes['cash_register_data_id'] : '';
        $this->bank_name =  key_exists('bank_name', $attributes) ? $attributes['bank_name'] : '';
    }
}
