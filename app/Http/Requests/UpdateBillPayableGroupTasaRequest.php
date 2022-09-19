<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use App\Rules\BillPayableExists;

use App\Repositories\BillsPayableRepository;

class UpdateBillPayableGroupTasaRequest extends FormRequest
{
    use AmountCurrencyTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(BillsPayableRepository $repo)
    {
        $payment_count = $repo->getBillPayablePaymentsCountByGroupID($this->group_id);
        $count = isset($payment_count->count) ? $payment_count->count : 0;
        return $count === 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $total_rules = ['required', new BadFormattedAmount, 'gt:0'];
 
        $rules = [
            'group_tasa' => $total_rules,
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
            'group_tasa' => $this->formatAmount($this->group_tasa),
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'group_tasa' => 'Tasa',
        ];
    }

    
}
