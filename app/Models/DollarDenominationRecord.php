<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DollarDenominationRecord extends Model
{
    use HasFactory;

    protected $connection = 'web_services_db';
    protected $table = 'dollar_denomination_records';
    public $timestamps = false;

    protected $fillable = [
        'quantity',
        'denominations_id',
        'cash_register_data_id',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->quantity = key_exists('quantity', $attributes) ? $attributes['quantity'] : '';
        $this->denomination = key_exists('denomination', $attributes) ? $attributes['denomination'] : '';
        $this->cash_register_data_id =  key_exists('cash_register_data_id', $attributes) ? $attributes['cash_register_data_id'] : '';
    }
}
