<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ZPrinterRecord;

class ZPrinter extends Model
{
    use HasFactory;

    protected $connection = 'web_services_db';
    protected $table = 'z_printers';

    public function __construct(array $attributes = array()){
        parent::__construct($attributes);
        $this->id = key_exists('id', $attributes) ? $attributes['id'] : '';
    }

    
    protected $fillable = [
        'id',
    ];

    public function z_printer_record()
    {
        return $this->hasMany(ZPrinterRecord::class);
    }
}
