<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Models\CashRegisterData;

class GetCashRegisterRequest extends Request
{
   
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        $cash_register_data = CashRegisterData::find($this->route('id'));

        return (!is_null($cash_register_data) && $cash_register_data->status === "EN EDICION");
    }
}
