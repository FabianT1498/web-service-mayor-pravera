<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CashRegister extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'cash_registers';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'worker_name',
        'cash_register_user',
        'user_name',
        'total_dollar_cash',
        'total_dollar_denominations',
        'total_bs_denominations',
        'total_point_sale_bs',
        'total_pago_movil_bs',
        'total_point_sale_dollar',
        'date',
        'cash_register_data_id'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->worker_name = key_exists('worker_name', $attributes) ? $attributes['worker_name'] : '';
        $this->user_name =  key_exists('user_name', $attributes) ? $attributes['user_name'] : '';
        $this->cash_register_user =  key_exists('cash_register_user', $attributes) ? $attributes['cash_register_user'] : '';
        $this->total_dollar_cash = key_exists('total_dollar_cash', $attributes) ? $attributes['total_dollar_cash'] : 0;
        $this->total_pago_movil_bs = key_exists('total_pago_movil_bs', $attributes) ? $attributes['total_pago_movil_bs'] : 0;
        $this->total_dollar_denominations = key_exists('total_dollar_denominations', $attributes) ? $attributes['total_dollar_denominations'] : 0;
        $this->total_bs_denominations = key_exists('total_bs_denominations', $attributes) ? $attributes['total_bs_denominations'] : 0;
        $this->total_point_sale_bs = key_exists('total_point_sale_bs', $attributes) ? $attributes['total_point_sale_bs'] : 0;
        $this->total_point_sale_dollar = key_exists('total_point_sale_dollar', $attributes) ? $attributes['total_point_sale_dollar'] : 0;
        $this->total_zelle = key_exists('total_zelle', $attributes) ? $attributes['total_zelle'] : 0;
        $this->date = key_exists('date', $attributes) ? $attributes['date'] : '';
        $this->cash_register_data_id = key_exists('id', $attributes)  ? $attributes['id'] : '';
    }

    // public function getDateAttribute($date)
    // {
    //     return (new Carbon($date))->format('d-m-Y');
    // }
}
