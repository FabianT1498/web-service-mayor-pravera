<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <!-- Styles -->
        <style>
            /** Normalized styles */
            * {
                box-sizing: border-box;
            }

            html {
                font-size: 16px;
            }

            body * { 
                box-sizing: border-box;
                font-size: 1rem;
            }

            table {
                border-collapse: collapse;
                text-align: left;
	            vertical-align: middle;
                border: 1px solid black;
                display: inline-table;
                caption-side: top;
            }

            caption {
                display: table-caption;
                width: 100%;
                border: 1px solid #ccc;
                box-sizing: border-box;
                border-top: none;
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
                padding: 0.2rem;
                border: 1px solid #ccc;
            }

            p {
                margin: 0;
            }

            h1 {
                font-size: 3.2rem;
            }

            .stripe {
                background: repeating-linear-gradient(
                45deg,
                #606dbc,
                #606dbc 10px,
                #465298 10px,
                #465298 20px
                );
            }

            .container {
                width: 800px;
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

            .bg-grey-600 {
                background-color: #b8b8b8;
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


            .w-30p{
                width: 30%;
            }

            .w-35p {
                width: 35%;
            }

            .w-70p{
                width: 70%;
            }

            .w-40p {
                width: 40%;
            }

            .w-80p{
                width: 80%;
            }

            .w-90p{
                width: 90%;
            }

            .w-full {
                width: 100%;
            }

            .text-lg {
                font-size: 2rem;
            }

            .font-semibold {
                font-weight: 500;
            }

            .flex {
                display: flex;
            }

            .space-between {
                justify-content: space-between
            }

            .mb-2 {
                margin-bottom: 0.5rem;
            }

            .mb-4 {
                margin-bottom: 1rem;
            }

            .mb-8 {
                margin-bottom: 2rem;
            }

            .mb-12 {
                margin-bottom: 3rem;
            }

            .w-full{
                width: 100%;
            }
            
            .pr-10{
                margin-right: 5rem;
            }

            .text-center {
                text-align: center;
            }

            .absolute {
                position: absolute;
            }

            .left50p {
                left: 50%;
            }

            .translate-50p{
                transform: translate(-50%);
            }

            .relative {
                position: relative;
            }

        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
            <div class="container">
                <div class="w-full mb-8 clearfix">
                    <div class="left">
                        <span>El mayorista</span>
                    </div>
                    <div class="right border-solid border-1 pr-10">
                        <p class="mb-1"><span class="font-semibold">Fecha del arqueo:</span> {{ date('d-m-Y', strtotime($cash_register->date)) }}</p>
                        <p class="mb-1"><span class="font-semibold">Usuario de la caja:</span> {{ $cash_register->cash_register_user }}</p>
                        <p class="mb-1"><span class="font-semibold">Responsable de la caja:</span> {{ $cash_register->worker_name }}</p>
                        <p><span class="font-semibold">Registro creado por:</span> {{ $cash_register->user_name }}</p>
                    </div>
                </div>

                <div class="w-80p mb-8">
                    <h1 class="text-center">Totales de entradas de dinero</h1>
                </div>

                <div class="w-80p mb-8">
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
                                <td class="text-center">{{ $cash_register->total_dollar_cash . ' ' . $currency_signs['dollar'] }}</td>
                                <td class="text-center">{{ $totals_from_safact->dolares . ' ' . $currency_signs['dollar'] }}</td>
                                <td 
                                    class="text-center {{  $differences['dollar_cash'] > 0 
                                        ? 'text-blue-400' 
                                        : ( $differences['dollar_cash'] < 0 
                                            ? 'text-red-400' 
                                            : '' ) }}"
                                >
                                        {{ $differences['dollar_cash'] . ' ' . $currency_signs['dollar']}}
                                </td> 
                            </tr>
                            <tr>
                                <th>Bs en efectivo</td>
                                <td class="text-center">{{ $cash_register->total_bs_cash . ' ' . $currency_signs['bs'] }}</td>
                                <td class="text-center">{{ $totals_from_safact->bolivares . ' ' . $currency_signs['bs'] }}</td>
                                <td 
                                    class="text-center {{ ($differences['bs_cash'] > 0) 
                                        ? 'text-blue-400' 
                                        : ( $differences['bs_cash'] < 0 
                                            ? 'text-red-400' 
                                            : '' )}}"
                                >
                                        {{ $differences['bs_cash'] . ' ' . $currency_signs['bs'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>Punto de venta Bs</th>
                                <td class="text-center">{{ $cash_register->total_point_sale_bs . ' ' . $currency_signs['bs'] }}</td>
                                <td class="text-center">{{ ($totals_e_payment[$user][$date]['01']['bs'] 
                                    + $totals_e_payment[$user][$date]['02']['bs']) . ' ' . $currency_signs['bs']}}</td>
                                <td 
                                    class="text-center {{ $differences['point_sale_bs'] > 0 
                                        ? 'text-blue-400' 
                                        : ( $differences['point_sale_bs'] < 0 
                                            ? 'text-red-400' 
                                            : '' )}}"
                                >
                                        {{ $differences['point_sale_bs'] . ' ' . $currency_signs['bs'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>Pago móvil y transferencias</th>
                                <td class="text-center">{{ $cash_register->total_pago_movil_bs . ' ' . $currency_signs['bs'] }}</td>
                                <td class="text-center">{{ $totals_e_payment[$user][$date]['05']['bs'] . ' ' . $currency_signs['bs']}}</td>
                                <td 
                                    class="text-center {{$differences['pago_movil_bs'] > 0 
                                        ? 'text-blue-400' 
                                        : ( $differences['pago_movil_bs'] < 0 
                                            ? 'text-red-400' 
                                            : '' )}}"
                                >
                                        {{ $differences['pago_movil_bs'] . ' ' . $currency_signs['bs'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>Punto de venta $</th>
                                <td class="text-center">{{ $cash_register->total_point_sale_dollar . ' ' . $currency_signs['dollar']}}</td>
                                <td class="text-center">{{ $totals_e_payment[$user][$date]['08']['dollar'] . ' ' . $currency_signs['dollar']  }}</td>
                                <td 
                                    class="text-center  {{ $differences['point_sale_dollar'] > 0 
                                        ? 'text-blue-400' 
                                        : ( $differences['point_sale_dollar'] < 0 
                                            ? 'text-red-400' 
                                            : '' )}}"
                                >
                                        {{ $differences['point_sale_dollar'] . ' ' . $currency_signs['dollar'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>Zelle</th>
                                <td class="text-center">{{ $cash_register->total_zelle . ' ' . $currency_signs['dollar']}}</td>
                                <td class="text-center">{{ $totals_e_payment[$user][$date]['07']['dollar'] . ' ' . $currency_signs['dollar'] }}</td>
                                <td 
                                    class="text-center {{ $differences['zelle'] > 0 
                                        ? 'text-blue-400' 
                                        : ( $differences['zelle'] < 0 
                                            ? 'text-red-400' 
                                            : '' )}}"
                                >
                                        {{ $differences['zelle'] . ' ' . $currency_signs['dollar'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="w-80p mb-8">
                    <h1 class="text-center">Totales de billetes tangibles</h1>
                </div>

                <div class="w-80p mb-8 clearfix">
                    <div class="w-40p left">
                        <table>
                            <caption class="text-center bg-grey-400 ">Cantidad de billetes por denominación (Bolivares)</caption>
                            <thead>
                                <tr>
                                    <th>Denominación</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal (Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($denominations_bolivar as $record)
                                    <tr>
                                        <td class="text-center">{{ $record->denomination }}</td>
                                        <td class="text-center">{{ $record->quantity }}</td>
                                        <td class="text-center">{{ $record->quantity * $record->denomination }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-grey-600">
                                    <td>&nbsp;</td>
                                    <td class="font-semibold">Total</td>
                                    <td class="bg-grey-600 text-center">{{ $total_denominations_bolivar }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-40p right">
                        <table>
                            <caption class="text-center bg-grey-400">Cantidad de billetes por denominación (Dolares)</caption>
                            <thead>
                                <tr>
                                    <th>Denominación</th>
                                    <th>Cantidad</th>
                                    <th>Total ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($denominations_dollar as $record)
                                    <tr>
                                        <td class="text-center">{{ $record->denomination }}</td>
                                        <td class="text-center">{{ $record->quantity }}</td>
                                        <td class="text-center">{{ $record->quantity * $record->denomination }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-grey-600">
                                    <td>&nbsp;</td>
                                    <td class="font-semibold">Total</td>
                                    <td class="text-center">{{ $total_denominations_dollar }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
    </body>
</html>
