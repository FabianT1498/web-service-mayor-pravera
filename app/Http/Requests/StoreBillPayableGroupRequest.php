<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use App\Rules\BillPayableExists;

use App\Repositories\BillsPayableRepository;

class StoreBillPayableGroupRequest extends FormRequest
{
    use AmountCurrencyTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(BillsPayableRepository $repo)
    {
        /**
         * Validaciones:
         * 1. Validar que cada una de las facturas que se van a agrupar no tengan pagos
         * dado que no se puede integrar una factura en un grupo si ya tiene un pago individual
         * o un pago grupal.
         * 
         * 2. Validar que cada una de las facturas no esten pagadas
         */
        if (count($this->bills) > 0){

            $bills_payable_keys = implode(" OR ", array_map(function($item){
                return "(bills_payable.cod_prov = '" . $item['cod_prov'] . "' AND bills_payable.nro_doc = '" . $item['nro_doc'] . "')";
            }, $this->bills));
    
            $bills = $repo->getBillsPayableByIds($bills_payable_keys)->get();
    
            // Verifica si existe un lote de facturas sin facturas asociadas para este proveedor.
            $empty_bill_group = $repo->getBillPayableGroups($this->cod_prov)
                ->havingRaw("SUM(COALESCE(bills_payable.amount, 0)) = 0.00")
                ->first();
    
            $are_valid_bills = true;
    
            foreach($bills as $bill){
                if ((floor(floatval($bill->MontoPagado . 'El') * 100) / 100) > 0.00){
                    $are_valid_bills = false;
                    return false;
                }
            }

            return empty($empty_bill_group) && $are_valid_bills;
        }

        return false;
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
            'cod_prov' => ['required'],
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
            'bills' => $bills,
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'numeroD' => 'Número de documento',
            'cod_prov' => 'Codigo de proveedor',
            'billType' => 'Tipo de factura',
            'tasa' => 'Tasa',
            'amount' => 'Monto total de la factura',
            'fechaE' => 'Fecha de emision',
        ];
    }
}
