<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;

use App\Rules\BillPayablePaymentBsIsUnique;

use App\Rules\BillPayableGroupExists;

use App\Repositories\BillsPayableRepository;

class StoreBillPayableGroupPaymentRequest extends FormRequest
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

        return $group && !is_null($group->ScheduleID);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $total_rules = ['required', new BadFormattedAmount, 'gt:0'];

        $bill_payable_group_exists_validation =  (new BillPayableGroupExists())->setData(['id' => $this->group_id]);

        $rules = [
            'group_id' => ['required', $bill_payable_group_exists_validation],
            'amount' => $total_rules,
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . Carbon::now()->format('Y-m-d')],
            'payment_currency' => ['required']
        ];

        if (isset($this->payment_currency) && $this->payment_currency === array_keys(config('constants.CURRENCIES'))[1]){
            $rules['foreign_currency_payment_method'] = ['required'];
            $rules['retirement_date'] = ['required', 'date_format:Y-m-d', 'after_or_equal:' . $this->date,
                'before_or_equal:' . Carbon::now()->format('Y-m-d')];
        } else {
            $bill_payable_payment_bs_is_unique = (new BillPayablePaymentBsIsUnique)->setData(['ref_number' => $this->ref_number, 'bank_name' => $this->bank_name]);
            $rules['tasa'] = $total_rules;
            $rules['ref_number'] = ['required'];
            $rules['bank_name'] = ['required'];
            array_push($rules['bank_name'], $bill_payable_payment_bs_is_unique); 
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
            'amount' => $this->formatAmount($this->amount),
            'tasa' => $this->formatAmount($this->tasa),
            'date' => isset($this->date) ? Carbon::createFromFormat('d-m-Y', $this->date)->format('Y-m-d') : null,
            'retirement_date' => isset($this->retirement_date) ? Carbon::createFromFormat('d-m-Y', $this->retirement_date)->format('Y-m-d') : null,
            'group_id' => $this->group_id
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'amount' => 'Monto a pagar',
            'tasa' => 'Tasa',
            'date' => 'Fecha de pago',
            'ref_number' => 'Numero de referencia',
            'retirement_date' => 'Fecha de retiro',
            'bank' => 'Banco'
        ];
    }

    
}
