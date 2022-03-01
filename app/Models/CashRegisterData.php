<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\DollarCashRecord;
use App\Models\BsCashRecord;
use App\Models\PointSaleBsRecord;
use App\Models\PointSaleDollarRecord;
use App\Models\BsDenominationRecord;
use App\Models\DollarDenominationRecord;
use App\Models\ZelleRecord;

class CashRegisterData extends Model
{
    use HasFactory;

    protected $dates = [
        'date',
	];

    protected $fillable = [
        'user_id',
        'cash_register_user',
        'date',
        'worker_name',
        'status',
    ];

    public function __construct(array $attributes = array(), $user_id)
    {
        parent::__construct($attributes);

        $this->user_id = $user_id;
        $this->date =  $attributes['date'];
        $this->cash_register_user =  $attributes['cash_register_id'];
        $this->worker_name = $attributes['cash_register_worker'];
        $this->status = "EN EDICION";
    }

    public function dollar_cash_records()
    {
        return $this->hasMany(DollarCashRecord::class);
    }

    public function bs_cash_records()
    {
        return $this->hasMany(BsCashRecord::class);
    }

    public function point_sale_bs_records()
    {
        return $this->hasMany(PointSaleBsRecord::class);
    }

    public function point_sale_dollar_records()
    {
        return $this->hasMany(PointSaleDollarRecord::class);
    }

    public function bs_denomination_records()
    {
        return $this->hasMany(BsDenominationRecord::class);
    }

    public function dollar_denomination_records()
    {
        return $this->hasMany(DollarDenominationRecord::class);
    }

    public function zelle_records()
    {
        return $this->hasMany(ZelleRecord::class);
    }    
}
