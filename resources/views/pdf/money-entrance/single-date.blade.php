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
                font-size: 1rem;
            }

            p {
                margin: 0;
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

            table {
                border-collapse: collapse;
                text-align: left;
	            vertical-align: middle;
                border: 1px solid black;
                display: inline-table;
            }

            .w-30p{
                width: 30%;
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

            .mb-4 {
                margin-bottom: 1rem;
            }

            .mb-8 {
                margin-bottom: 2rem;
            }

            .mb-6 {
                margin-bottom: 3rem;
            }

            .w-full{
                width: 100%;
            }

            .pr-10{
                margin-right: 5rem;
            }

        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="container">
            <div class="w-full mb-6 flex clearfix">
                <div class="left">Logo</div>
                <div class="right pr-10">
                    <p>Entradas de dinero</p>
                    <p><span class="font-semibold">Fecha: </span>{{ $start_date }}</p>
                </div>
            </div>
            <div class="flex space-between">
                @foreach($data as $key => $record)
                    <table class="w-30p mb-4">
                        <thead>
                            <tr>
                                <th>{{ $key }}</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <th>Dolares en efectivo</th>
                                <td>{{ $record['dollar_cash'] . ' ' . $currency_signs['dollar'] }}</td>
                            </tr>
                            <tr>
                                <th>Bs en efectivo</td>
                                <td>{{ $record['bs_cash'] . ' ' . $currency_signs['bs'] }}</td>
                            </tr> -->
                            <tr>
                                <th>Punto de venta Bs (Pagos en Debito)</th>
                                <td>{{ $record['bs_debit'] . ' ' . $currency_signs['bs'] }}</td>
                            </tr>
                            <tr>
                                <th>Punto de venta en Bs (Pagos en Credito)</th>
                                <td>{{ $record['bs_credit'] . ' ' . $currency_signs['bs'] }}</td>
                            </tr>
                            <tr>
                                <th>Todo ticket Bs</th>
                                <td>{{ $record['todo_ticket'] . ' ' . $currency_signs['bs'] }}</td>
                            </tr>
                            <tr>
                                <th>Transf. y Pago Movíl Bs</th>
                                <td>{{ $record['transferencia_pago_movil'] . ' ' . $currency_signs['bs'] }}</td>
                            </tr>
                            <tr>
                                <th>Punto de venta en $</th>
                                <td>{{ $record['point_sale_dollar'] . ' ' . $currency_signs['dollar']}}</td>
                            </tr>
                            <tr>
                                <th>Zelle</th>
                                <td>{{ $record['zelle'] . ' ' . $currency_signs['dollar']}}</td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    </body>
</html>
