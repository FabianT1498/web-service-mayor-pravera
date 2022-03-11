<?php 

$currencies = ['BOLIVAR' => "bolivar", 'DOLLAR' => "dollar"];
$currency_signs = [
    $currencies['BOLIVAR'] => "Bs.s",
    $currencies['DOLLAR'] => "$"
];

$cash_register_status = [
    'EDITING' => 'EDITING',
    'COMPLETED' => 'COMPLETED'
];

return [
	'CURRENCIES' => $currencies,
	'CURRENCY_SIGNS' => $currency_signs,
    'BAD_FORMATTED_AMOUNT' => -1,
    'CASH_REGISTER_STATUS' => $cash_register_status
];
