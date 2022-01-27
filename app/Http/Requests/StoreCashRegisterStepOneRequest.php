<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;

class StoreCashRegisterStepOneRequest extends FormRequest
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
        $date_format = 'd-m-Y';
        $today_date = Date::now()->format('d-m-Y');

        $rules = [
            'cash_register_id' => [
                'required',
                // 'exists:cash_register,id',
            ],
            'cash_register_worker' => [
                'required',
                // 'exists:cash_register_worker,id',
            ],
            'liquid_money_dollars' => [
                'required',
                'gte:0',
            ],
            'liquid_money_bs' => [
                'required',
                'gte:0',
            ],
            'payment_zelle' => [
                'required',
                'gte:0',
            ],
            'debit_card_payment_bs' => [
                'required',
                'gte:0',
            ],
            'debit_card_payment_dollar' => [
                'required',
                'gte:0',
            ]
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
        var_dump($this->date);

        $inputs = [
            'liquid_money_dollars' => $this->formatAmount($this->liquid_money_dollars),
            'liquid_money_bs' => $this->formatAmount($this->liquid_money_bs),
            'payment_zelle' => $this->formatAmount($this->payment_zelle),
            'debit_card_payment_bs' => $this->formatAmount($this->debit_card_payment_bs),
            'debit_card_payment_dollar' => $this->formatAmount($this->debit_card_payment_dollar),
        ];
        
        $this->merge($inputs);
    }
}
