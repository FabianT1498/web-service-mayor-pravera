<?php 

$currencies = ['BOLIVAR' => 'bolivar', 'DOLLAR' => 'dollar'];
$currency_signs = [
    $currencies['BOLIVAR'] => 'Bs.s',
    $currencies['DOLLAR'] => '$'
];

return [
	'CURRENCIES' => $currencies,
	'CURRENCY_SIGNS' => $currency_signs,
];
