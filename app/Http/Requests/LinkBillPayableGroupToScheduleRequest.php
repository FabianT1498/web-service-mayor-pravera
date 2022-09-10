<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Traits\AmountCurrencyTrait;

use App\Repositories\BillsPayableRepository;

class LinkBillPayableGroupToScheduleRequest extends FormRequest
{
    use AmountCurrencyTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(BillsPayableRepository $repo)
    {
        $group = $repo->getBillPayableGroupByID($this->groupID);

        $paid_amount = $this->formatAmount($group->MontoPagado);

        return $group && $paid_amount === 0.00 
            && config("constants.BILL_PAYABLE_STATUS." . $group->Estatus) === config("constants.BILL_PAYABLE_STATUS.NOTPAID");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'groupID' => ['required', 'exists:bill_payable_groups,id'],
            'scheduleID' => ['required', 'exists:bill_payable_schedules,id'],
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'id' => 'Número de lote',
        ];
    }    
}
