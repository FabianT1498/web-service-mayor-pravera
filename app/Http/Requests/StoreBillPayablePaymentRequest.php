<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use App\Rules\BillPayableExists;
use App\Rules\BillPayableHasTasa;

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

        $total_rules = ['required', new BadFormattedAmount, 'gt:0'];

        $bill_payable_exists_validation =  (new BillPayableExists($repo))->setData(['nro_doc' => $this->nro_doc, 'cod_prov' => $this->cod_prov]);
        $bill_payable_has_tasa = (new BillPayableHasTasa())->setData(['nro_doc' => $this->nro_doc, 'cod_prov' => $this->cod_prov]);

        $rules = [
            'cod_prov' => ['required'],
            'nro_doc' => ['required', $bill_payable_exists_validation, $bill_payable_has_tasa],
            'amount' => $total_rules,
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . Carbon::now()->format('Y-m-d')],
        ];

        if (isset($this->is_dollar) && $this->is_dollar === '1'){
            $rules['foreign_currency_payment_method'] = ['required'];
            $rules['retirement_date'] = ['required', 'date_format:Y-m-d', 'after_or_equal:' . $this->date,
                'before_or_equal:' . Carbon::now()->format('Y-m-d')];
        } else {
            $rules['tasa'] = $total_rules;
            $rules['ref_number'] = ['required'];
            $rules['bank_name'] = ['required'];
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
            'retirement_date' => isset($this->retirement_date) ? Carbon::createFromFormat('d-m-Y', $this->retirement_date)->format('Y-m-d') : null
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
            'reference_number' => 'Numero de referencia'
        ];
    }

    
}
