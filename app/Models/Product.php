<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProductSuggestion;

use Carbon\Carbon;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'cod_prod',
        'descrip'
    ];

    public $timestamps = false;

 
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->cod_prod = key_exists('cod_prod', $attributes) ? $attributes['cod_prod'] : '';
        $this->descrip = key_exists('descrip', $attributes) ? $attributes['descrip'] : '';
    }

    public function product_suggestions()
    {
        return $this->hasMany(ProductSuggestion::class);
    }
}
