<?php

$bill_payable_types = [
    'NE' => "N.E",
    'FAC' => "FISCAL"
];

$bill_payable_status = [
    'NOTPAID' => "POR PAGAR",
    'PAID' => "PAGADA",
];

$bill_payable_schedule_status = [
    'CLOSED' => "CERRADO",
    'PROCESSING' => "EN PROCESO"
];

$foreing_currency_bill_payment_methods = [
    'CASH' => "EFECTIVO",
    'DEPOSIT' => "DEPOSITO",
    'ZELLE' => 'ZELLE'
];

$bill_payable_action = [
    'GROUPED' => 0,
    'SCHEDULED' => 1,
    'FAILED_GROUPING' => 2,
    'FAILED_SCHEDULING' => 3,
];

$bill_payable_action_mess = [
    0 => "Se han agrupado las facturas",
    1 => "Se ha programado la factura",
    2 => "No se ha podido agrupar las facturas",
    3 => "No se ha podido programar la factura"
];

