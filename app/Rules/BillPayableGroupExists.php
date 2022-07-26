<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\BillPayableGroup;

class BillPayableGroupExists implements Rule
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
        $group = BillPayableGroup::whereRaw("id = ?", [$this->data['id']])->first();
    
        return !is_null($group);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Este grupo no existe';
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
