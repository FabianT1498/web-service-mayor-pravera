<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ZPrinterRecord;

class ZPrinterRecord extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'z_printers_record';

    protected $fillable = [
        'cash_register_users_id',
        'printer_id',
        'date',
        'user_id',
        'status'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->user_id = key_exists('user_id', $attributes) ? $attributes['user_id'] : '';
        $this->date =  key_exists('date', $attributes) ? $attributes['date'] : '';
        $this->cash_register_users_id = key_exists('cash_register_users_id', $attributes) ? $attributes['cash_register_users_id'] : '';
        $this->printer_id = key_exists('printer_id', $attributes) ? $attributes['printer_id'] : '';
        $this->status = key_exists('status', $attributes) ? $attributes['status'] : '';

    }
    
    public function z_bills()
    {
        return $this->hasMany(ZBill::class);
    }
}
