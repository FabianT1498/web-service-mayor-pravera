<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use App\Rules\BillPayableExists;
use App\Rules\BillPayableIsNotGrouped;
use App\Rules\BillPayableIsScheduled;

use App\Repositories\BillsPayableRepository;

class ShowBillPayableRequest extends FormRequest
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

        $bill_payable_exists_validation =  (new BillPayableExists($repo))->setData(['nro_doc' => $this->numero_d, 'cod_prov' => $this->cod_prov]);
        $bill_payable_is_grouped =  (new BillPayableIsNotGrouped())->setData(['nro_doc' => $this->numero_d, 'cod_prov' => $this->cod_prov]);
        // $bill_payable_is_not_scheduled =  (new BillPayableIsScheduled())->setData(['nro_doc' => $this->numero_d, 'cod_prov' => $this->cod_prov]);

        $rules = [
            'cod_prov' => ['required'],
            'numero_d' => ['required', $bill_payable_exists_validation, $bill_payable_is_grouped],
        ];

        return $rules;
    }

    public function prepareForValidation() 
    {
        $this->merge([
            'cod_prov' => $this->route('cod_prov'),
            'numero_d' => $this->route('numero_d'),
        ]);
    }

    public function attributes()
    {
        return [
            'numero_d' => 'Número de documento',
            'cod_prov' => 'Codigo de proveedor',
        ];
    }
}
