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
    public function authorize(BillsPayableRepository $repo)
    {
        $bill_payment_count = $repo->getBillPayablePaymentsCount($this->nro_doc, $this->cod_prov);
        $count = isset($bill_payment_count->count) ? $bill_payment_count->count : 0;
        return $count === 0;
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
            'cod_prov' => ['required'],
            'nro_doc' => ['required', $bill_payable_exists_validation],
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
