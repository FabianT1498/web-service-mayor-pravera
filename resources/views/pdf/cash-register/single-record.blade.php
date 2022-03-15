<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <!-- Styles -->
        <style>
            * {
                box-sizing: border-box;
            }

            html {
                font-size: 16px;
            }

            body * { 
                box-sizing: border-box;
            }

            p {
                margin: 0;
            }

            .container {
                width: 90%;
                margin: 0 auto;
            }

            .px-2 {
                padding: 0 1rem ;
            }

            .p-2 {
                padding: 0.5rem;
            }

            .border-r-1px {
                border-right-width: 1px;
            }

            .bg-grey-400 {
                background-color: #e9e9e9;
            }

            .border-solid {
                border: 0 solid #000;
            }

            .left {
                float: left;
            }

            .right {
                float: right;
            }

            .clearfix::after {
                content: "";
                clear: both;
                display: table;
            }

            .text-red-400 {
                color: #ec0000;
            }

            .text-blue-400 {
                color: #1717ff;
            }

            .h3 {
                font-size: 1.4rem;
                font-weight: bold;
            }

            .h3::after{
                display: block;
                height: 1px;
                background-color: #000;
                content: ' ';
                width: 200px;
                margin-bottom: 5px;
            }

            .border-1 {
                border-width: 1px;
            }

            .mx-auto {
                margin: 0 auto;
            }

            .mb-2 {
                margin-bottom: 1rem;
            }

            .mb-1 {
                margin-bottom: 0.5rem;
            }

            table {
                border-collapse: collapse;
                text-align: center;
	            vertical-align: middle;
                border: 1px solid black;
            }

            tbody tr:nth-child(even) {
                background-color: #eee;
            }

            tbody th {
                background-color: #36c;
                color: #fff;
                text-align: left;
            }

            tbody tr:nth-child(even) th {
                background-color: #25c;
            }

            thead {
                background-color: #575757;
                color: white;
            }

            thead th {
                width: 25%;
            }

            th, td {
                padding: 0.5rem;
            }

            .font-semibold {
                font-weight: 500;
            }

        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <header class="container clearfix mx-auto mb-2">
            <div class="left font-">
                <span>El mayorista</span>
            </div>
            <div class="right border-solid border-1 p-2">
                <p class="mb-1"><span class="font-semibold">Fecha del arqueo:</span> {{ $cash_register->date }}</p>
                <p class="mb-1"><span class="font-semibold">Usuario de la caja:</span> {{ $cash_register->cash_register_user }}</p>
                <p class="mb-1"><span class="font-semibold">Responsable de la caja:</span> {{ $cash_register->worker_name }}</p>
                <p><span class="font-semibold">Registro creado por:</span> {{ $cash_register->user_name }}</p>
            </div>
        </header>
        <section class="container">
            <table>
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Total ingresado en el sistema</th>
                        <th>Total recuperado de SAINT</th>
                        <th>Diferencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Dolares en efectivo</th>
                        <td>{{ $cash_register->total_dollar_cash . ' ' . $currency_signs['dollar'] }}</td>
                        <td>{{ (key_exists('dollar_cash', $cash_register_saint) 
                            ? $cash_register_saint['dollar_cash'] 
                            : 0) . ' ' . $currency_signs['dollar']
                        }}</td>
                        <td>
                        
                        </td> 
                    </tr>
                    <tr>
                        <th>Bs en efectivo</td>
                        <td>{{ $cash_register->total_bs_cash . ' ' . $currency_signs['bs'] }}</td>
                        <td>{{ (key_exists('bs_cash', $cash_register_saint) 
                            ? $cash_register_saint['bs_cash'] 
                            : 0) . ' ' . $currency_signs['bs']
                        }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Punto de venta Bs</th>
                        <td>{{ $cash_register->total_point_sale_bs . ' ' . $currency_signs['bs'] }}</td>
                        <td>
                            @if (key_exists('bs_debit', $cash_register_saint) && key_exists('bs_credit', $cash_register_saint))
                                {{  $cash_register_saint['bs_debit'] + $cash_register_saint['bs_credit']}}
                            @elseif(key_exists('bs_debit', $cash_register_saint))
                                {{  $cash_register_saint['bs_debit'] }}
                            @elseif(key_exists('bs_credit', $cash_register_saint))
                                {{  $cash_register_saint['bs_credit'] }}
                            @else
                                __('0')
                            @endif
                            {{ ' ' . $currency_signs['bs']}}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Punto de venta $</th>
                        <td>{{ $cash_register->total_point_sale_dollar . ' ' . $currency_signs['dollar']}}</td>
                        <td>{{ (key_exists('point_sale_dollar', $cash_register_saint) 
                            ? $cash_register_saint['point_sale_dollar'] 
                            : 0) . ' ' . $currency_signs['dollar'] 
                        }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Zelle</th>
                        <td>{{ $cash_register->total_zelle . ' ' . $currency_signs['dollar']}}</td>
                        <td>{{ (key_exists('zelle', $cash_register_saint) 
                            ? $cash_register_saint['zelle'] 
                            : 0) . ' ' . $currency_signs['dollar'] 
                        }}</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Bolivares en físico (Denominaciones)</th>
                        <td>{{ $cash_register->total_bs_denominations . ' ' . $currency_signs['bs'] }}</td>
                        <td class="bg-grey-400">&nbsp;</td>
                        <td class="bg-grey-400">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Dolares en efectivo (Denominaciones)</th>
                        <td>{{ $cash_register->total_dollar_denominations . ' ' . $currency_signs['dollar']}}</td>
                        <td class="bg-grey-400">&nbsp;</td>
                        <td class="bg-grey-400">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </body>
</html>
