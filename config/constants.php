<?php 

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
    'DB_CONN_MAP' => $db_conn_map
];
