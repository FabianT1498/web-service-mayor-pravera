<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $timestamp = false;

    protected $dates = [
        'date',
	];

    protected $fillable = [
        'id',
        'worker_name',
        'user_name',
        'total_dollar_cash',
        'total_bs_cash',
        'total_dollar_denominations',
        'total_bs_denominations',
        'total_point_sale_bs',
        'total_point_sale_dollar',
        'total_zelle',
        'date',
    ];
    
}
