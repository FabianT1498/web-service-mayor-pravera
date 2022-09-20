<?php

require __DIR__.'\constants\product.php';
require __DIR__.'\constants\cash-register.php';
require __DIR__.'\constants\bill-payable.php';

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
    'POINT_SALE_METHODS' => $point_sale_methods,
    'BILL_PAYABLE_TYPE' => $bill_payable_types,
    'BILL_PAYABLE_STATUS' => $bill_payable_status,
    'BILL_PAYABLE_SCHEDULE_STATUS' => $bill_payable_schedule_status,
    'FOREIGN_CURRENCY_BILL_PAYMENT_METHODS' => $foreing_currency_bill_payment_methods,
    'BILL_PAYABLE_ACTION' => $bill_payable_action,
    'BILL_PAYABLE_ACTION_MESS' => $bill_payable_action_mess,
];
