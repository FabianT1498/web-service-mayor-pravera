<?php

namespace App\Http\Requests;

use App\Http\Requests\Api\FormRequest;
use App\Http\Traits\AmountCurrencyTrait;

class StoreDollarExchangeRequest extends FormRequest
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

        $rules = [
            'bs_exchange' => [
                'json',
                'bail',
                'required',
                'gte:0',
            ],
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
            'bs_exchange' => $this->formatAmount($this->bs_exchange),
        ];
        
        $this->merge($inputs);
    }    
}
