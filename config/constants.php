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

$suggestion_status = [
    'ACCEPTED' => 'ACCEPTED',
    'PROCESSING' => 'PROCESSING',
    'DENIED' => 'DENIED'
];

$suggestion_status_es_ui = [
    'ACCEPTED' => 'ACEPTADO',
    'PROCESSING' => 'EN PROCESO',
    'DENIED' => 'DENEGADO',
    'NO SUGGESTION' => 'SIN SUGERENCIAS',
];

$db_connections_name = [
    'PRAV' => 'Pravera',
    'MAYOR' => 'Mayorista'
];

$db_conn_map = [
    'PRAV' => 'saint_db_pravera',
    'MAYOR' => 'saint_db'
];


return [
    'SUGGESTION_STATUS' => $suggestion_status,
    'SUGGESTION_STATUS_ES_UI' => $suggestion_status_es_ui,
    'DB_CONN_NAMES' => $db_connections_name,
    'DB_CONN_MAP' => $db_conn_map,
    'CASH_TIPO_FAC' => $cash_tipo_fac,
    'COD_PAGO' => $cod_pago,
	'CURRENCIES' => $currencies,
	'CURRENCY_SIGNS' => $currency_signs,
    'BAD_FORMATTED_AMOUNT' => -1,
    'CASH_REGISTER_STATUS' => $cash_register_status,
    'POINT_SALE_METHODS' => $point_sale_methods
];
