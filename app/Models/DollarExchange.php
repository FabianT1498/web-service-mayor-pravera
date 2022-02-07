<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DollarExchange extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'dollar_exchange';

    protected $fillable = [
        'bs_exchange',
    ];
    
}
