<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Date;


class StoreCashRegisterStepOne extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'date' => [
                'required',
                'date_format:'.$date_format,
                'date_equals:'.$today_date,
            ],
            'cash_register_id' => [
                'required',
                'exists:cash_register,id'
            ],
            'cash_register_owner' => [
                'required',
                'exists:cash_register_worker,id',
            ],
            'liquid_money_dollars' => [
                'required',
                'min:0',
            ],
            'liquid_money_bs' => [
                'required',
                'min:0'
            ],
            'payment_zelle' => [
                'required',
                'min:0'
            ],
            'debit_card_payment_bs' => [
                'required',
                'min:0'
            ],
            'debit_card_payment_dollar' => [
                'required',
                'min:0'
            ]
        ];

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {

        $inputs = [
            'date' => Date::createFromFormat('d-m-Y', $this->date),
            'liquid_money_dollars' => (float) $this->liquid_money_dollars,
            'liquid_money_bs' => (float) $this->liquid_money_bs,
            'payment_zelle' => (float) $this->payment_zelle,
            'debit_card_payment_bs' => (float) $this->debit_card_payment_bs,
            'debit_card_payment_dollar' => (float) $this->debit_card_payment_dollar,
        ];

        $this->merge($inputs);
    }
}
