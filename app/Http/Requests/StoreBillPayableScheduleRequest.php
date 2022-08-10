<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Carbon\Carbon;

class StoreBillPayableScheduleRequest extends FormRequest
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
       
        $rules = [
            'cash_register_user' => ['required', 'exists:cash_register_users,name'],
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . Carbon::now()->format('Y-m-d')]
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
        $inputs = [
            'total_dollar_denominations' => $this->formatAmount($this->total_dollar_denominations),
            'total_bs_denominations' => $this->formatAmount($this->total_bs_denominations),
            'date' => isset($this->date) ? Carbon::createFromFormat('d-m-Y', $this->date)->format('Y-m-d') : null
        ];
 
        $this->merge($inputs);
    }

    public function attributes()
    {
        return [
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha final',
        ];
    }
}
