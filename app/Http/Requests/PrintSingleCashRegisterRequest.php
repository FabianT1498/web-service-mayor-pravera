<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CashRegisterData;

class PrintSingleCashRegisterRequest extends FormRequest
{
   
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cash_register_data = CashRegisterData::where('id', $this->route('id'))->first();

        return (!is_null($cash_register_data));
    }

    public function rules(){
        return [];
    }
}
