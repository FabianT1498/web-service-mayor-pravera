<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;

use App\Repositories\BillsPayableRepository;

use App\Models\BillPayable;

class LinkBillPayableToScheduleRequest extends FormRequest
{
    use AmountCurrencyTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(BillsPayableRepository $repo)
    {
        $bill = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$this->numeroD, $this->codProv])->first();
        $bill_payment_count = $repo->getBillPayablePaymentsCount($this->numeroD, $this->codProv);
        $count = isset($bill_payment_count->count) ? $bill_payment_count->count : 0;
        return !$bill || ($bill && $count === 0 && config("constants.BILL_PAYABLE_STATUS." . $bill->status) === config("constants.BILL_PAYABLE_STATUS.NOTPAID"));
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
            'codProv' => ['required'],
            'numeroD' => ['required'],
            'billType' => ['required'],
            'tasa' => ['required', new BadFormattedAmount, 'gte:0'],
            'isDollar' => ['required'],
            'amount' => $total_rules,
            'provDescrip' => ['required'],
            'fechaE' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . Carbon::now()->format('Y-m-d')],
            'scheduleID' =>  ['required', 'exists:bill_payable_schedules,id'],
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
            'amount' => $this->formatAmount($this->amount),
            'tasa' => $this->formatAmount($this->tasa),
            'fechaE' => isset($this->fechaE) ? Carbon::createFromFormat('d-m-Y', $this->fechaE)->format('Y-m-d') : null,
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'numeroD' => 'Número de documento',
            'codProv' => 'Codigo de proveedor',
            'billType' => 'Tipo de factura',
            'tasa' => 'Tasa',
            'amount' => 'Monto total de la factura',
            'fechaE' => 'Fecha de emision',
            'scheduleID' => 'Programación'
        ];
    }

    
}
