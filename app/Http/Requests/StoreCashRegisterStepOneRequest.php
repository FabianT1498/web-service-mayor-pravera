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

        $total_rules = ['required', new BadFormattedAmount, 'gt:0'];

        $rules = [];

        if (count($this->dollar_cash_record) > 0){
            $rules['dollar_cash_record.*'] = $total_rules;
        }
        
        if (count($this->bs_cash_record) > 0){
            $rules['bs_cash_record.*'] = $total_rules;
        }

        if (count($this->dollar_denominations_record) > 0){
            $rules['dollar_denominations_record.*'] = ['required', 'gte:0'];
        }

        if (count($this->bs_denominations_record) > 0){
            $rules['bs_denominations_record.*'] = ['required', 'gte:0'];
        }

        if (count($this->point_sale_bs_record) > 0){
            $rules['point_sale_bs_record.*.credit'] = $total_rules;
            $rules['point_sale_bs_record.*.debit'] = $total_rules;
        }

        if (count($this->zelle_record) > 0){
            $rules['zelle_record.*'] = $total_rules;
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

        // $inputs = [
        //     'total_dollar_cash' => $this->has('total_dollar_cash') ? $this->formatAmount($this->total_dollar_cash) : null,
        //     'total_bs_cash' => $this->has('total_bs_cash') ? $this->formatAmount($this->total_bs_cash) : null,
        //     'total_dollar_denominations' => $this->has('total_dollar_denominations') ? $this->formatAmount($this->total_dollar_denominations) : null,
        //     'total_bs_denominations' => $this->has('total_bs_denominations') ? $this->formatAmount($this->total_bs_denominations) : null,
        //     'total_point_sale_dollar' => $this->has('total_point_sale_dollar') ? $this->formatAmount($this->total_point_sale_dollar) : null,
        //     'total_point_sale_bs' => $this->has('total_point_sale_bs') ? $this->formatAmount($this->total_point_sale_bs) : null,
        //     'total_zelle' => $this->has('total_zelle') ? $this->formatAmount($this->total_zelle) : null
        // ];
        

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

        // if (!is_null($inputs['total_point_sale_bs']) && $inputs['total_point_sale_bs'] > 0){
            
        if ($this->has('point_sale_bs_bank') && $this->has('point_sale_bs_debit') 
                && $this->has('point_sale_bs_credit')){
            
            if ((count($this->point_sale_bs_bank) === count($this->point_sale_bs_debit))
                    && (count($this->point_sale_bs_bank) === count($this->point_sale_bs_credit))){    
                foreach ($this->point_sale_bs_bank as $key => $value){
                    $inputs['point_sale_bs_record'][$value] = [
                        'credit' =>  $this->formatAmount($this->point_sale_bs_credit[$key]),
                        'debit' =>  $this->formatAmount($this->point_sale_bs_debit[$key])
                    ];
                }
            } else {
                $inputs['point_sale_bs_record'] = []; 
            }
        } else {
            $inputs['point_sale_bs_record'] = [];
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
