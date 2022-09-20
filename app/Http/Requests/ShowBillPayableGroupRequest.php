<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\BillPayableGroupExists;

class ShowBillPayableGroupRequest extends FormRequest
{

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
    public function rules()
    {

        $bill_payable_group_exists_validation =  (new BillPayableGroupExists())->setData(['id' => $this->id]);
        
        $rules = [
            'id' => ['required', $bill_payable_group_exists_validation],
        ];

        return $rules;
    }

    public function prepareForValidation() 
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function attributes()
    {
        return [
            'id' => 'Lote del grupo',
        ];
    }
}
