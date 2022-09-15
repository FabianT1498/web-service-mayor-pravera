<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;

use App\Repositories\BillsPayableRepository;

use App\Models\BillPayableGroup;

class UpdateBillPayableGroupRequest extends FormRequest
{
    use AmountCurrencyTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(BillsPayableRepository $repo)
    {
        $new_group = BillPayableGroup::whereRaw("id = " . $this->id)->first();

        $bills_payable_keys = implode(" OR ", array_map(function($item){
            return "(bills_payable.cod_prov = '" . $item['cod_prov'] . "' AND bills_payable.nro_doc = '" . $item['nro_doc'] . "')";
        }, $this->bills));

        $bills = $repo->getBillsPayableByIds($bills_payable_keys)->get();

        $are_valid_bills = true;

        foreach($bills as $bill){
            if ((floor(floatval($bill->MontoPagado . 'El') * 100) / 100) > 0.00){
                $are_valid_bills = false;
                return false;
            }
        }

        return $new_group 
            // && config('constants.BILL_PAYABLE_STATUS.' . $new_group->status) === config('constants.BILL_PAYABLE_STATUS.PROCESSING') 
            && $are_valid_bills;
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
            'bills.*.cod_prov' => ['required'],
            'bills.*.nro_doc' => ['required'],
            'bills.*.bill_type' => ['required'],
            'bills.*.tasa' => ['required', new BadFormattedAmount, 'gte:0'],
            'bills.*.is_dollar' => ['required'],
            'bills.*.amount' => $total_rules,
            'bills.*.descrip_prov' => ['required'],
            'bills.*.emission_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . Carbon::now()->format('Y-m-d')],
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
        $bills = array_map(function($item){
            return array(
                'cod_prov' => $item['codProv'],
                'nro_doc' => $item['numeroD'],
                'descrip_prov' => $item['provDescrip'],
                'bill_type' => $item['billType'],
                'tasa' => $this->formatAmount($item['tasa']),
                'amount' => $this->formatAmount($item['amount']),
                'is_dollar' => $item['isDollar'],
                'emission_date' => isset($item['fechaE']) ? Carbon::createFromFormat('d-m-Y', $item['fechaE'])->format('Y-m-d') : null,
            );
        }, $this->bills);

        $inputs = [
            'bills' => $bills
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'nro_doc' => 'Número de documento',
            'cod_prov' => 'Codigo de proveedor',
            'bill_type' => 'Tipo de factura',
            'tasa' => 'Tasa',
            'amount' => 'Monto total de la factura',
            'emission_date' => 'Fecha de emision',
        ];
    }
}
