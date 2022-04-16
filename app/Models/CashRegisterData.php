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
use App\Models\PagoMovilRecord;

use Carbon\Carbon;


class CashRegisterData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cash_register_user',
        'date',
        'worker_id',
        'status',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->user_id = key_exists('user_id', $attributes) ? $attributes['user_id'] : '';
        $this->date =  key_exists('date', $attributes) ? $attributes['date'] : '';
        $this->cash_register_user = key_exists('cash_register_user', $attributes) ? $attributes['cash_register_user'] : '';
        $this->worker_id = key_exists('worker_id', $attributes) ? $attributes['worker_id'] : '';
        $this->status = key_exists('status', $attributes) ? $attributes['status'] : config('constants.CASH_REGISTER_STATUS.EDITING');
    }

    // public function getDateAttribute($date)
    // {
    //     return (new Carbon($date))->format('d-m-Y');
    // }

    public function dollar_cash_records()
    {
        return $this->hasMany(DollarCashRecord::class);
    }

    public function bs_cash_records()
    {
        return $this->hasMany(BsCashRecord::class);
    }

    public function pago_movil_bs_records()
    {
        return $this->hasMany(PagoMovilRecord::class);
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
