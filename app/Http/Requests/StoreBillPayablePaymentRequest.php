<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use App\Rules\BillPayableExists;

use App\Repositories\BillsPayableRepository;

class StoreBillPayablePaymentRequest extends FormRequest
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

        $total_rules = ['required', new BadFormattedAmount, 'gte:0'];

        $bill_payable_exists_validation =  (new BillPayableExists($repo))->setData(['nro_doc' => $this->nro_doc, 'cod_prov' => $this->cod_prov]);
       
        $rules = [
            'nro_doc' => ['required', $bill_payable_exists_validation],
            'amount' => $total_rules,
            'tasa' => $total_rules,
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . Carbon::now()->format('Y-m-d')],
            'referenceNumber' => ['required'],
            'bank' => ['required']
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
            'date' => isset($this->date) ? Carbon::createFromFormat('d-m-Y', $this->date)->format('Y-m-d') : null
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'nro_doc' => 'Número de documento',
            'cod_prov' => 'Codigo de proveedor',
            'amount' => 'Monto a pagar',
            'tasa' => 'Tasa',
            'date' => 'Fecha de pago',
            'referenceNumber' => 'Numero de referencia'
        ];
    }

    
}
