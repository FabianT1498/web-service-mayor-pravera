<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'workers';

    public function __construct(array $attributes = array()){
        parent::__construct($attributes);

        $this->name = array_key_exists('name', $attributes) ? $attributes['name'] : '';
    }

    
    protected $fillable = [
        'name',
    ];
}
