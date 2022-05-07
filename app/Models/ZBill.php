<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZBill extends Model
{
    protected $connection = 'caja_mayorista';
    protected $table = 'z_bills';

    protected $fillable = [
        'nro_z',
        'printer_record_id',

    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->nro_z = key_exists('nro_z', $attributes) ? $attributes['nro_z'] : '';
        $this->printer_record_id =  key_exists('printer_record_id', $attributes) ? $attributes['printer_record_id'] : '';
    }  
}
