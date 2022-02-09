<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DollarExchange extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'bs_exchange',
    ];

    protected $connection = 'caja_mayorista';
    protected $table = 'dollar_exchange';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    
        $this->bs_exchange = key_exists('bs_exchange', $attributes) ? $attributes['bs_exchange'] : 0;
    }

    
}
