<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use Carbon\Carbon;

class StoreProductSuggestionRequest extends FormRequest
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
       
        $total_rules = ['required', new BadFormattedAmount, 'gte:0'];

        $rules = [
            'cod_prod' => ['required', 'exists:saint_db.SAPROD,CodProd'],
            'percent_suggested' => $total_rules
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
            'percent_suggested' => $this->formatAmount($this->percentSuggested),
            'cod_prod' => $this->codProd
        ];

        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'percent_suggested' => 'porcentaje de utilidad',
            'cod_prod' => 'código del producto',
        ];
    }
}
