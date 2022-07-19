<?php
namespace App\Http\Traits;

trait AmountCurrencyTrait {

    /**
     *  Format an amount with currency to a float number and then return it
     * ex: 12.23 Bs | 12.22 $ | 12.23 %
     *
     * @param  string  $amount
     * @return float $float_number
     */
    public function formatAmount($amount){

        if (is_null($amount)){
            return 0;
        }

        $arrAmount = explode(' ', $amount);
        $number = $arrAmount[0];
        
        $arrNumber = explode('.', $number);
        
        $integer = $arrNumber["0"] ?? null;
        $decimal = $arrNumber["1"] ?? null;

        $integerArr = explode(',', $integer);

        $integer = implode('', $integerArr);

        if (is_null($decimal) && is_numeric($integer)){
            return intval($integer);
        } else if (is_numeric($integer) && is_numeric($decimal)){
            $number_string = $integer . '.' . $decimal . 'El';
            return floatval($number_string);
        }
        
        return config('constants.BAD_FORMATTED_AMOUNT', -1);
    }
}