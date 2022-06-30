<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $connection = 'caja_mayorista';
    protected $table = 'notes';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'cash_register_data_id',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->title = key_exists('title', $attributes) ? $attributes['title'] : '';
        $this->description = key_exists('description', $attributes) ? $attributes['description'] : '';
        $this->cash_register_data_id =  key_exists('cash_register_data_id', $attributes) ? $attributes['cash_register_data_id'] : '';
    }
}
