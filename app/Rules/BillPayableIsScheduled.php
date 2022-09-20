<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\BillPayable;
use App\Http\Traits\AmountCurrencyTrait;


class BillPayableIsScheduled implements Rule
{
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
    
        return !is_null($bill->bill_payable_schedules_id);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La factura no esta programada, primero asignela a una programación';
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
