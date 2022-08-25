<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use App\Rules\BillPayableExists;

use App\Repositories\BillsPayableRepository;


class UpdateBillPayableTasaRequest extends FormRequest
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
    public function rules(BillsPayableRepository $repo)
    {

        $total_rules = ['required', new BadFormattedAmount, 'gt:0'];

        $bill_payable_exists_validation =  (new BillPayableExists($repo))->setData(['nro_doc' => $this->nro_doc, 'cod_prov' => $this->cod_prov]);
        
        $rules = [
            'nro_doc' => ['required', $bill_payable_exists_validation],
            'cod_prov' => ['required'],
            'bill_tasa' => $total_rules,
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
            'bill_tasa' => $this->formatAmount($this->bill_tasa),
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'bill_tasa' => 'Tasa',
        ];
    }

    
}
