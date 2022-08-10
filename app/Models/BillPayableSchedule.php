<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BillPayable;


use Carbon\Carbon;

class BillPayableSchedule extends Model
{
   
    protected $connection = 'web_services_db';
    protected $table = 'bill_payable_schedules';
    public $timestamps = false;

    protected $fillable = [
        'start_date',
        'end_date',
        'status'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->start_date = key_exists('start_date', $attributes) ? $attributes['start_date'] : '';
        $this->end_date =  key_exists('end_date', $attributes) ? $attributes['end_date'] : '';
        $this->status = key_exists('status', $attributes) ? $attributes['status'] : array_keys(config('constants.BILL_PAYABLE_SCHEDULE_STATUS'))[1];
    }

    public function bills_payable()
    {
        return $this->hasMany(BillPayable::class);
    }

    
}
