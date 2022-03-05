<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CashRegisterData;

class GetCashRegisterRequest extends FormRequest
{
   
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cash_register_data = CashRegisterData::where('id', $this->route('id'))->first();

    
        return (!is_null($cash_register_data) && $cash_register_data->status === 'EN EDICION');
    }

    public function rules(){
        return [];
    }
}
