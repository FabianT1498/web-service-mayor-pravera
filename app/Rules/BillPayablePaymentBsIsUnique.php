<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\BillPayablePaymentBs;

class BillPayablePaymentBsIsUnique implements Rule
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
        $bill_payment_bs = BillPayablePaymentBs::whereRaw("ref_number = ? AND bank_name = ?", [$this->data['ref_number'], $this->data['bank_name']])->first();
    
        return !$bill_payment_bs;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El número de referencia y el banco ya esta asociado a otro pago.';
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
