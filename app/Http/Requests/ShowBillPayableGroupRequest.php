<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\BillPayableGroupExists;

use App\Http\Traits\AmountCurrencyTrait;

use App\Repositories\BillsPayableRepository;

class ShowBillPayableGroupRequest extends FormRequest
{
    use AmountCurrencyTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(BillsPayableRepository $repo)
    {
        $group = $repo->getBillPayableGroupByID($this->id);

        return $group && $this->formatAmount($group->MontoTotal) > 0 && !is_null($group->ScheduleID);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $bill_payable_group_exists_validation =  (new BillPayableGroupExists())->setData(['id' => $this->id]);
        
        $rules = [
            'id' => ['required', $bill_payable_group_exists_validation],
        ];

        return $rules;
    }

    public function prepareForValidation() 
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function attributes()
    {
        return [
            'id' => 'Lote del grupo',
        ];
    }
}
