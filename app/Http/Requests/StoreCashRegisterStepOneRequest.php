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

        $total_rules= ['required', 'gte:0'];

        $rules = [
            // 'cash_register_id' => [
            //     'required',
            //     'exists:saint_db.SSUSRS,CodUsua',
            // ],
            'total_liquid_money_dollars' => [
                'bail',
                ...$total_rules
            ],
            'total_liquid_money_bs' => [
                ...$total_rules
            ],
            'total_liquid_money_dollars_denominations' => [
                ...$total_rules
            ],
            'total_liquid_money_bs_denominations' => [
                ...$total_rules
            ],
            'total_point_sale_dollar' => [
                ...$total_rules
            ], 
            'total_point_sale_bs' => [
                ...$total_rules
            ],
            'total_zelle_record' => [
                ...$total_rules
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
            'total_liquid_money_dollars' => $this->formatAmount($this->total_liquid_money_dollars),
            'total_liquid_money_bs' => $this->formatAmount($this->total_liquid_money_bs),
            'total_liquid_money_dollars_denominations' => $this->formatAmount($this->total_liquid_money_dollars_denominations),
            'total_liquid_money_bs_denominations' => $this->formatAmount($this->total_liquid_money_bs_denominations),
            'total_point_sale_dollar' => $this->formatAmount($this->total_point_sale_dollar),
            'total_point_sale_bs' => $this->formatAmount($this->total_point_sale_bs),
            'total_zelle_record' => $this->formatAmount($this->total_zelle_record)
        ];

        // if (isset($this->new_cash_register_worker)){
        //     $inputs += ['new_cash_register_worker' => ucwords($this->new_cash_register_worker)];
        // }
        
        $this->merge($inputs);
    }
}
