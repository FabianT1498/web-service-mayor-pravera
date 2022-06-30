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

$cash_tipo_fac = ['A' => 'bs_cash', 'B' => 'dollar_cash'];

$tipo_fac = ['A' => 'efectivo', 'B' => 'cheque'];

$cod_pago = [
    '01' => 'bs_debit',
    '02' => 'bs_credit',
    '03' => 'todo_ticket',
    '05' => 'transferencia_pago_movil',
    '07' => 'zelle',
    '08' => 'point_sale_dollar'
];

$point_sale_methods = [
    'DEBIT' => 'DEBIT',
    'CREDIT' => 'CREDIT',
    'AMEX' => 'AMEX',
    'TODOTICKET' => 'TODOTICKET',
];

return [
    'CASH_TIPO_FAC' => $cash_tipo_fac,
    'COD_PAGO' => $cod_pago,
	'CURRENCIES' => $currencies,
	'CURRENCY_SIGNS' => $currency_signs,
    'BAD_FORMATTED_AMOUNT' => -1,
    'CASH_REGISTER_STATUS' => $cash_register_status,
    'POINT_SALE_METHODS' => $point_sale_methods
];
