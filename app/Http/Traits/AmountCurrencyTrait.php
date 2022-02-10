<?php
namespace App\Http\Traits;

trait AmountCurrencyTrait {

    /**
     *  Format an amount with currency to a float number and then return it
     * ex: 12.23 Bs | 12.22 $
     *
     * @param  string  $amount
     * @return float $float_number
     */
    public function formatAmount($amount){

        if (is_null($amount)){
            return 0;
        }

        $number = explode(' ', $amount)[0];
        $arr = explode(',', $number);
        
        $integer = $arr["0"] ?? null;
        $decimal = $arr["1"] ?? null;
        
        $formated_integer = implode(explode(".", $integer));
        
        $number_string = $formated_integer . '.' . $decimal . 'El';
        $float_number = floatval($number_string);

        return $float_number;
    }
}