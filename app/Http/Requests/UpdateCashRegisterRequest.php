<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

use App\Http\Traits\AmountCurrencyTrait;

use App\Rules\BadFormattedAmount;
use App\Models\CashRegisterData;



class UpdateCashRegisterRequest extends FormRequest
{
    use AmountCurrencyTrait;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cash_register_data = CashRegisterData::where('id', $this->route('id'))->first();
        return (!is_null($cash_register_data) && $cash_register_data->status === config('constants.CASH_REGISTER_STATUS.EDITING'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        $total_rules = ['required', new BadFormattedAmount, 'gte:0'];

        $rules = [
            'cash_register_user' => ['required', 'exists:cash_register_users,name'],
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . Carbon::now()->format('Y-m-d')]
        ];

        if ($this->has('exist_cash_register_worker') 
                && $this->exist_cash_register_worker === '1' 
                    && $this->has('new_cash_register_worker')){
            $rules['new_cash_register_worker'] = ['required', 'unique:caja_mayorista.workers,name', 'max:100'];
        } else {
            $rules['worker_id'] = ['required', 'exists:workers,id'];
        }

        if (count($this->dollar_cash_record) > 0){
            $rules['dollar_cash_record.*'] = $total_rules;
        } 
        
        if (count($this->bs_cash_record) > 0){
            $rules['bs_cash_record.*'] = $total_rules;
        }

        if (count($this->pago_movil_record) > 0){
            $rules['pago_movil_record.*'] = $total_rules;
        }

        // if ($this->total_dollar_denominations > 0){
            $rules['dollar_denominations_record.*'] = ['required', 'gte:0'];
        // }

        // if ($this->total_bs_denominations > 0){
            $rules['bs_denominations_record.*'] = ['required', 'gte:0'];
        // }

        if (count($this->point_sale_bs) > 0){
            // $rules['point_sale_bs_bank.*'] = ['exists:caja_mayorista.banks,name'];
            // $rules['point_sale_bs_debit.*'] = ['required', new BadFormattedAmount];
            // $rules['point_sale_bs_credit.*'] = ['required', new BadFormattedAmount];
            // $rules['point_sale_bs_amex.*'] = ['required', new BadFormattedAmount];
            // $rules['point_sale_bs_todoticket.*'] = ['required', new BadFormattedAmount];

            $rules['point_sale_bs.*.bank_name'] = ['exists:caja_mayorista.banks,name'];
            $rules['point_sale_bs.*.cancel_debit'] = ['required', new BadFormattedAmount];
            $rules['point_sale_bs.*.cancel_credit'] = ['required', new BadFormattedAmount];
            $rules['point_sale_bs.*.cancel_amex'] = ['required', new BadFormattedAmount];
            $rules['point_sale_bs.*.cancel_todoticket'] = ['required', new BadFormattedAmount];
        }

        if (count($this->zelle_record) > 0){
            $rules['zelle_record.*'] = $total_rules;
        }

        if ($this->total_point_sale_dollar >= 0){
            $rules['total_point_sale_dollar'] = $total_rules;
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
            'date' => isset($this->date) ? Carbon::createFromFormat('d-m-Y', $this->date)->format('Y-m-d') : null,
            'total_dollar_denominations' => $this->formatAmount($this->total_dollar_denominations),
            'total_bs_denominations' => $this->formatAmount($this->total_bs_denominations)
        ];

        // if (!is_null($inputs['total_dollar_cash']) && $inputs['total_dollar_cash'] > 0){
        if ($this->has('dollar_cash_record')){
            $inputs['dollar_cash_record'] = array_reduce($this->dollar_cash_record, function($acc, $value){
                if ($value > 0){
                    $acc[] = $this->formatAmount($value);
                }

                return $acc;
            }, []);

            // $inputs['dollar_cash_record'] = array_map(function($record){
            //     return $this->formatAmount($record);
            // }, $this->dollar_cash_record);
        } else {
            $inputs['dollar_cash_record'] = [];
        }
        // }
            
        // if (!is_null($inputs['total_bs_cash']) && $inputs['total_bs_cash'] > 0){
        if ($this->has('bs_cash_record')){
            $inputs['bs_cash_record'] = array_reduce($this->bs_cash_record, function($acc, $value){
                if ($value > 0){
                    $acc[] = $this->formatAmount($value);
                }

                return $acc;
            }, []);
            // $inputs['bs_cash_record'] = array_map(function($record){
            //     return $this->formatAmount($record);
            // }, $this->bs_cash_record);
        } else {
            $inputs['bs_cash_record'] = [];
        }
        // }

        if ($this->has('pago_movil_record')){
            $inputs['pago_movil_record'] = array_reduce($this->pago_movil_record, function($acc, $value){
                if ($value > 0){
                    $acc[] = $this->formatAmount($value);
                }

                return $acc;
            }, []);
            // $inputs['pago_movil_record'] = array_map(function($record){
            //     return $this->formatAmount($record);
            // }, $this->pago_movil_record);
        } else {
            $inputs['pago_movil_record'] = [];
        }

        // if (!is_null($inputs['total_dollar_denominations']) && $inputs['total_dollar_denominations'] > 0){
        if ($this->has('dollar_denominations_record')){
            foreach($this->dollar_denominations_record as $key => $value){
                    $inputs['dollar_denominations_record'][$key] = intval($value);
            }
        } else {
            $inputs['dollar_denominations_record'] = [];
        }
        // }

        // if (!is_null($inputs['total_bs_denominations']) && $inputs['total_bs_denominations'] > 0){
            if ($this->has('bs_denominations_record')){
                foreach($this->bs_denominations_record as $key => $value){
                    $inputs['bs_denominations_record'][$key] = intval($value);
                }
            } else {
                $inputs['bs_denominations_record'] = [];
            }
        // }

        if ($this->has('point_sale_bs_bank')){
            $inputs['point_sale_bs'] = array_map(function($bank, $debit, $credit, $amex, $todoticket){
                return [
                    'bank_name' => $bank,
                    'cancel_debit' => $this->formatAmount($debit),
                    'cancel_credit' => $this->formatAmount($credit),
                    'cancel_amex' => $this->formatAmount($amex),
                    'cancel_todoticket' => $this->formatAmount($todoticket),
                ];
            }, $this->point_sale_bs_bank, $this->point_sale_bs_debit, $this->point_sale_bs_credit,
                    $this->point_sale_bs_amex, $this->point_sale_bs_todoticket);
        } else {
            $inputs['point_sale_bs'] = [];
        }

        
        if ($this->has('total_point_sale_dollar')){
            $inputs['total_point_sale_dollar'] = $this->formatAmount($this->total_point_sale_dollar);
        }

        // }
        
        // if(!is_null($inputs['total_zelle']) && $inputs['total_zelle'] > 0){
        if ($this->has('zelle_record')){
            $inputs['zelle_record'] = array_reduce($this->zelle_record, function($acc, $value){
                if ($value > 0){
                    $acc[] = $this->formatAmount($value);
                }

                return $acc;
            }, []);
            // $inputs['zelle_record'] = array_map(function($record){
            //     return $this->formatAmount($record);
            // }, $this->zelle_record);
        } else {
            $inputs['zelle_record'] = [];
        }
         
        // }

        // if (isset($this->new_cash_register_worker)){
        //     $inputs += ['new_cash_register_worker' => ucwords($this->new_cash_register_worker)];
        // }
        
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'cash_register_user' => 'codigo de usuario',
            'date' => 'fecha',
            'worker_id' => 'nombre del trabajador',
            'new_cash_register_worker' => 'nombre del nuevo trabajador',
            'dollar_cash_record' => 'entrada de dolar',
            'pago_movil_record' => 'entrada de pago movil',
            'dollar_denominations_record' => 'denominación de dolar',
            'bs_denominations_record' => 'denominación de bolivar',
            'point_sale_bs.*.bank_name' => 'banco',
            'point_sale_bs.*.cancel_debit' => 'entrada de tarjeta de debito',
            'point_sale_bs.*.cancel_credit' => 'entrada de tarjeta de credito',
            'point_sale_bs.*.cancel_amex' => 'entrada de AMEX',
            'point_sale_bs.*.cancel_todoticket' => 'entrada de todoticket',
            'zelle_record' => 'entrada de zelle',
            'total_point_sale_dollar' => 'entrada del punto de venta internacional',
        ];
    }
}
