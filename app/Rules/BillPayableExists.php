<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Repositories\BillsPayableRepository;

class BillPayableExists implements Rule
{
    /**
     * The user repository instance.
     *
     * @var \App\Repositories\BillsPayableRepository
     */
    protected $bill_payable_repo;
   
    /**
     * Create a new rule instance.
     * @param  \App\Repositories\BillsPayableRepository  $repo
     * @return void
     */
    public function __construct(BillsPayableRepository $repo)
    {
        //
        $this->bill_payable_repo = $repo;
    }   

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bill = $this->bill_payable_repo->getBillPayable($this->data['nro_doc'],
            $this->data['cod_prov']);
        return !is_null($bill);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El :attribute no corresponde a ninguna factura';
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
