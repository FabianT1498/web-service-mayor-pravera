<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;


class StoreCashRegisterRequest extends FormRequest
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

        $total_rules = ['required', new BadFormattedAmount, 'gt:0'];

        $rules = [
            'cash_register_id' => ['required', 'exists:saint_db.SSUSRS,CodUsua']
        ];

        if ($this->has('new_cash_register_worker')
                && !empty($this->new_cash_register_worker)){
            $rules['new_cash_register_worker'] = ['required', 'unique:caja_mayorista.workers,name', 'max:100'];
        } else {
            $rules['cash_register_worker'] = ['required', 'exists:workers,id'];
        }

        if (count($this->dollar_cash_record) > 0){
            $rules['dollar_cash_record.*'] = $total_rules;
        }
        
        if (count($this->bs_cash_record) > 0){
            $rules['bs_cash_record.*'] = $total_rules;
        }

        if ($this->total_dollar_denominations > 0){
            $rules['dollar_denominations_record.*'] = ['required', 'gte:0'];
        }

        if ($this->total_bs_denominations > 0){
            $rules['bs_denominations_record.*'] = ['required', 'gte:0'];
        }

        if (count($this->point_sale_bs_bank) > 0){
            $rules['point_sale_bs_bank.*'] = ['exists:caja_mayorista.banks,name'];
            $rules['point_sale_bs_debit.*'] = ['required', new BadFormattedAmount];
            $rules['point_sale_bs_credit.*'] = ['required', new BadFormattedAmount];
        }

        if (count($this->zelle_record) > 0){
            $rules['zelle_record.*'] = $total_rules;
        }

        if ($this->total_point_sale_dollar > 0){
            $rules['total_point_sale_dollar'] = $total_rules;
        }
        
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
            'total_dollar_denominations' => $this->formatAmount($this->total_dollar_denominations),
            'total_bs_denominations' => $this->formatAmount($this->total_bs_denominations)
        ];

        // if (!is_null($inputs['total_dollar_cash']) && $inputs['total_dollar_cash'] > 0){
        if ($this->has('dollar_cash_record')){
            $inputs['dollar_cash_record'] = array_map(function($record){
                return $this->formatAmount($record);
            }, $this->dollar_cash_record);
        } else {
            $inputs['dollar_cash_record'] = [];
        }
        // }
            
        // if (!is_null($inputs['total_bs_cash']) && $inputs['total_bs_cash'] > 0){
        if ($this->has('bs_cash_record')){
            $inputs['bs_cash_record'] = array_map(function($record){
                return $this->formatAmount($record);
            }, $this->bs_cash_record);
        } else {
            $inputs['bs_cash_record'] = [];
        }
        // }

        // if (!is_null($inputs['total_dollar_denominations']) && $inputs['total_dollar_denominations'] > 0){
        if ($this->has('dollar_denominations_record')){
            foreach($this->dollar_denominations_record as $key => $value){
                    $inputs['dollar_denominations_record'][$key] = intval($value);
            }
        } else {
            $inputs['dollar_denominations_record'] = [];
        }
        // }

        // if (!is_null($inputs['total_bs_denominations']) && $inputs['total_bs_denominations'] > 0){
            if ($this->has('bs_denominations_record')){
                foreach($this->bs_denominations_record as $key => $value){
                    $inputs['bs_denominations_record'][$key] = intval($value);
                }
            } else {
                $inputs['bs_denominations_record'] = [];
            }
        // }

        if ($this->has('point_sale_bs_bank')){
            if ($this->has('point_sale_bs_debit')){
                $inputs['point_sale_bs_debit'] = array_map(function($value){
                    return $this->formatAmount($value);
                }, $this->point_sale_bs_debit);
            } else {
                $inputs['point_sale_bs_debit'] = [];
            }
    
            if ($this->has('point_sale_bs_credit')){
                $inputs['point_sale_bs_credit'] = array_map(function($value){
                    return $this->formatAmount($value);
                }, $this->point_sale_bs_credit);
            } else {
                $inputs['point_sale_bs_credit'] = [];
            }
        } else {
            $inputs['point_sale_bs_bank'] = [];
        }

        
        if ($this->has('total_point_sale_dollar')){
            $inputs['total_point_sale_dollar'] = $this->formatAmount($this->total_point_sale_dollar);
        }

        // }
        
        // if(!is_null($inputs['total_zelle']) && $inputs['total_zelle'] > 0){
        if ($this->has('zelle_record')){
            $inputs['zelle_record'] = array_map(function($record){
                return $this->formatAmount($record);
            }, $this->zelle_record);
        } else {
            $inputs['zelle_record'] = [];
        }     
        // }

        // if (isset($this->new_cash_register_worker)){
        //     $inputs += ['new_cash_register_worker' => ucwords($this->new_cash_register_worker)];
        // }
        
        $this->merge($inputs);
    }
}
