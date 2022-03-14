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

            .container {
                width: 90%;
                margin: 0 auto;
            }

            .px-1rem {
                padding: 0 1rem ;
            }

            .border-r-1px {
                border-right-width: 1px;
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



        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <header >
            <div class="container clearfix">
                <div class="left">
                    <span>El mayorista</span>
                </div>
                <div class="right">
                    <p>Fecha del arqueo: {{ $cash_register->date }}</p>
                    <p>Usuario de la caja: {{ $cash_register->cash_register_user }}</p>
                    <p>Responsable de la caja: {{ $cash_register->worker_name }}</p>
                    <p>Registro creado por: {{ $cash_register->user_name }}</p>
                </div>
            </div>
        </header>
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
                    <td>Dolares en efectivo</td>
                    <td>{{ $cash_register->total_dollar_cash}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Bs en efectivo</td>
                    <td>{{ $cash_register->total_bs_cash}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Punto de venta Bs</th>
                    <td>{{ $cash_register->total_point_sale_bs}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Punto de venta $</th>
                    <td>{{ $cash_register->total_point_sale_dollar}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Bolivares en físico</th>
                    <td>{{ $cash_register->total_bs_denominations}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Dolares en efectivo (Denominaciones)</th>
                    <td>{{ $cash_register->total_dollar_denominations}}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Zelle</th>
                    <td>{{ $cash_register->total_zelle}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
