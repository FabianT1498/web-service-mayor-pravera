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
        'date',
        'cash_register_id',
        'cash_register_worker',
        'liquid_money_dollars',
        'liquid_money_bs',
        'payment_zelle',
        'debit_card_payment_bs',
        'debit_card_payment_dollar'
    ];
    
}
