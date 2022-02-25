<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\NonZeroTotalSum;


class StoreCashRegisterStepOneRequest extends FormRequest
{
    use AmountCurrencyTrait;
    
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
        return var_dump($this->all());

        $date_format = 'd-m-Y';

        $rules = [
            'cash_register_id' => [
                'required',
                'exists:saint_db.SSUSRS,CodUsua',
            ],
            'liquid_money_dollars' => [
                'bail',
                'required',
                'gte:0',
                new NonZeroTotalSum,
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

        // if (!isset($this->cash_register_worker)){
        //     $rules += ["new_cash_register_worker" => array(
        //         'required',
        //         'unique:caja_mayorista.workers,name'
        //     )];
        // } else {
        //     $rules += ['cash_register_worker' => array(
        //         'required',
        //         'exists:caja_mayorista.workers,id',
        //     )];
        // }

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
            'total_liquid_money_dollars' => $this->formatAmount($this->liquid_money_dollars),
            'total_liquid_money_bolivares' => $this->formatAmount($this->liquid_money_bs),
            'total_liquid_money_dollars_denominations' => $this->formatAmount($this->payment_zelle),
            'total_liquid_money_bolivares_denominations' => $this->formatAmount($this->debit_card_payment_bs),
            'total_point_sale_dollar' => $this->formatAmount($this->debit_card_payment_dollar),
        ];

        if (isset($this->new_cash_register_worker)){
            $inputs += ['new_cash_register_worker' => ucwords($this->new_cash_register_worker)];
        }
        
        $this->merge($inputs);
    }
}
