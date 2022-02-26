<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;


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
        $date_format = 'd-m-Y';

        $total_rules = ['required', new BadFormattedAmount, 'gte:0'];

        $rules = [
            // 'cash_register_id' => [
            //     'required',
            //     'exists:saint_db.SSUSRS,CodUsua',
            // ],
            'total_dollar_cash' => [
                'bail',
                ...$total_rules
            ],
            'total_bs_cash' => [
                ...$total_rules
            ],
            'total_dollar_denominations' => [
                ...$total_rules
            ],
            'total_bs_denominations' => [
                ...$total_rules
            ],
            'total_point_sale_dollar' => [
                ...$total_rules
            ], 
            'total_point_sale_bs' => [
                ...$total_rules
            ],
            'total_zelle' => [
                ...$total_rules
            ],
        ];

        if ($this->has('dollar_cash_record')){
            $rules[] = ['dollar_cash_record.*' => [...$total_rules]];
        }

        if ($this->has('bs_cash_record')){
            $rules[] = ['bs_cash_record.*' => [...$total_rules]];
        }

        if ($this->has('dollar_denominations_record')){
            $rules[] = ['dollar_denominations_record.*' => ['required', 'gte:0']];
        }

        if ($this->has('bs_denominations_record')){
            $rules[] = ['bs_denominations_record.*' => ['required', 'gte:0']];
        }

        if ($this->has('point_sale_bs_bank')){
            $rules[] = [
                'point_sale_bs_record.*.credit' => [...$total_rules],
                'point_sale_bs_record.*.debit' => [...$total_rules],
            ];
        }
        
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
            'total_dollar_cash' => $this->has('total_dollar_cash') ? $this->formatAmount($this->total_dollar_cash) : null,
            'total_bs_cash' => $this->has('total_bs_cash') ? $this->formatAmount($this->total_bs_cash) : null,
            'total_dollar_denominations' => $this->has('total_dollar_denominations') ? $this->formatAmount($this->total_dollar_denominations) : null,
            'total_bs_denominations' => $this->has('total_bs_denominations') ? $this->formatAmount($this->total_bs_denominations) : null,
            'total_point_sale_dollar' => $this->has('total_point_sale_dollar') ? $this->formatAmount($this->total_point_sale_dollar) : null,
            'total_point_sale_bs' => $this->has('total_point_sale_bs') ? $this->formatAmount($this->total_point_sale_bs) : null,
            'total_zelle' => $this->has('total_zelle') ? $this->formatAmount($this->total_zelle) : null
        ];

        if ($inputs['total_dollar_cash'] > 0){
            if ($this->has('dollar_cash_record')){
                $inputs['dollar_cash_record'] = array_map(function($record){
                    return $this->formatAmount($record);
                }, $this->dollar_cash_record);
            } else {
                $inputs['dollar_cash_record'] = null;
            }
        }

        if ($inputs['total_bs_cash'] > 0){
            if ($this->has('bs_cash_record')){
                $inputs['bs_cash_record'] = array_map(function($record){
                    return $this->formatAmount($record);
                }, $this->bs_cash_record);
            } else {
                $inputs['bs_cash_record'] = null;
            }
        }

        if ($inputs['total_dollar_denominations'] > 0){
            if ($this->has('dollar_denominations_record')){
                $inputs['dollar_denominations_record'] = array_map(function($key, $value){
                    return array($key, intval($value));
                }, array_keys($this->dollar_denominations_record), $this->dollar_denominations_record);
            } else {
                $inputs['dollar_denominations_record'] = null;
            }
        }

        if ($inputs['total_bs_denominations'] > 0){
            if ($this->has('bs_denominations_record')){
                $inputs['bs_denominations_record'] = array_map(function($key, $value){
                    return array($key, intval($value));
                }, array_keys($this->bs_denominations_record), $this->bs_denominations_record);
            } else {
                $inputs['bs_denominations_record'] = null;
            }
        }

        if ($inputs['total_point_sale_bs'] > 0){
            if ($this->has('point_sale_bs_bank') && $this->has('point_sale_bs_debit') 
                    && $this->has('point_sale_bs_credit')){
                
                if ((count($this->point_sale_bs_bank) === count($this->point_sale_bs_debit))
                        && (count($this->point_sale_bs_bank) === count($this->point_sale_bs_credit))){     
                    $inputs['point_sale_bs_record'] = array_map(function($key, $credit_val, $debit_val){
                        return array($key, ['credit' => $this->formatAmount($credit_val), 'debit' => $this->formatAmount($debit_val)]);
                    }, array_keys($this->point_sale_bs_bank), $this->point_sale_bs_credit, $this->point_sale_bs_debit);       
                } else {
                    $inputs['point_sale_bs_record'] = null; 
                }
                
            } else {
                $inputs['point_sale_bs_record'] = null;
            }
        }

        if ($inputs['total_zelle'] > 0 && $this->has('zelle_record')){
            $inputs['zelle_record'] = array_map(function($record){
                return $this->formatAmount($record);
            }, $this->zelle_record);
        }

        // if (isset($this->new_cash_register_worker)){
        //     $inputs += ['new_cash_register_worker' => ucwords($this->new_cash_register_worker)];
        // }
        
        $this->merge($inputs);
    }
}
