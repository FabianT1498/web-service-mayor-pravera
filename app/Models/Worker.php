<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'workers';

    
    protected $fillable = [
        'name',
    ];
}
