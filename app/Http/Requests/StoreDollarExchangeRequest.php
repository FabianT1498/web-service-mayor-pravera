<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDollarExchangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    
        $rules = [
            'bs_exchange' => [
                'bail',
                'required',
                'gte:0',
            ],
        ];

        return $rules;
    }

    private function formatAmount($amount){

        if (is_null($amount)){
            return 0;
        }

        $arr = explode(',', $amount, 2);
        $integer = $arr["0"] ?? null;
        $decimal = $arr["1"] ?? null;
        
        $formated_integer = implode(explode(".", $integer));
        
        $number_string = $formated_integer . '.' . $decimal . 'El';
        $float_number = floatval($number_string);

        var_dump($float_number);

        return $float_number;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        var_dump($this->bs_exchange);
        $inputs = [
            'bs_exchange' => $this->formatAmount($this->bs_exchange),
        ];
        
        $this->merge($inputs);
    }
}
