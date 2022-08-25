<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\BillPayable;
use App\Http\Traits\AmountCurrencyTrait;


class BillPayableHasTasa implements Rule
{
    use AmountCurrencyTrait;
   
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bill = BillPayable::whereRaw("nro_doc = ? AND cod_prov = ?", [$this->data['nro_doc'], $this->data['cod_prov']])->first();
        
        $bill->Tasa = $this->formatAmount($bill->Tasa);

        return $bill->Tasa > 0.00;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La factura no tiene una tasa definida';
    }

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
