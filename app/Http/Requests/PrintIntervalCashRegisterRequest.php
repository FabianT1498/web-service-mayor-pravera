<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CashRegisterData;

class PrintIntervalCashRegisterRequest extends FormRequest
{
   
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $new_start_date = date('Y-m-d', strtotime($this->route('start_date')));
        $new_finish_date = date('Y-m-d', strtotime($this->route('end_date')));

        $cash_register_data = CashRegisterData
            ::selectRaw('count(id) as number_of_records')
            ->where('status', config('constants.CASH_REGISTER_STATUS.COMPLETED'))
            ->whereBetween('cash_register_data.date', [$new_start_date, $new_finish_date]);
        
        return (!is_null($cash_register_data) && $cash_register_data->number_of_records > 0);
    }

    public function rules(){
        return [];
    }
}