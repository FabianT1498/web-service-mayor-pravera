<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductSuggestion extends Model
{
    use HasFactory;

    protected $connection = 'web_services_db';
    protected $table = 'product_suggestions';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $fillable = [
        'percent_suggested',
        'cod_prod',
        'user_name',
        'status',
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->percent_suggested = key_exists('percent_suggested', $attributes) ? $attributes['percent_suggested'] : '';
        $this->user_name =  key_exists('user_name', $attributes) ? $attributes['user_name'] : '';
        $this->cod_prod =  key_exists('cod_prod', $attributes) ? $attributes['cod_prod'] : '';
        $this->status =  key_exists('status', $attributes) ? $attributes['status'] : config('constants.SUGGESTION_STATUS.PROCESSING');
    }
}
