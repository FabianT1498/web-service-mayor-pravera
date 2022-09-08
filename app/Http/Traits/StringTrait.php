<?php
namespace App\Http\Traits;

trait StringTrait {

    public function isADateFormatDDMMYYYY($date, $date_separator = '-'){

        if ($date === ''){
            return false;
        }

        $date_splitted = explode($date_separator, $date);

        if(count($date_splitted) === 3){
 
            $date_cleaned = array_map(function($item){
                return preg_replace("/\D/", '', $item);
            }, $date_splitted);
 
            $is_a_date = true;
        
            forEach($date_cleaned as $number){
                if (!is_numeric($number)){
                    $is_a_date = false;
                    return false;
                }
            }
    
            if (!$is_a_date){
                return false;
            }
    
            return true;
        }
    
        return false;
    }

    public function charReplace($text = '', $char_to_replace = '-', $new_char = '/'){
    
        if ($text === ''){
            return '';
        }
    
        $text_splitted = explode($char_to_replace, $text);

        return implode($new_char, $text_splitted);
    }
}