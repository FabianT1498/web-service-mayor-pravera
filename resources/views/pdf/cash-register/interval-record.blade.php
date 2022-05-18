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

            .page-break {
                page-break-after: always;
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
                padding: 0.1rem;
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

            .p-2 {
                padding: 0.5rem;
            }

            .p-4 {
                padding: 1rem;
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
            <div class="w-full mb-4 clearfix">
                <div class="left">
                    <span>El mayorista</span>
                </div>
                <div class="right border-solid border-1 pr-10">
                    <p class="mb-2 font-semibold">Reporte de cierres de cajas</p>
                    <p class="mb-1">
                        @if ($start_date === $end_date)
                            Fecha: {{ $start_date }}
                        @else
                            Reporte por intervalo: {{ $start_date }}<br>
                            {{ " Hasta " . $end_date }}
                        @endif
                    </p>
                </div>
            </div>

            @foreach($saint_totals as $key_user => $dates)
                <div class="w-80p mb-8 mx-auto">
                    @if ($cash_registers_totals->has($key_user))
                        @foreach($dates as $key_date => $date)
                            @if($cash_registers_totals[$key_user]->has($key_date))
                                <table class="w-80p mb-8">
                                    <caption class="text-center w-80p bg-grey-400">{{ $key_user }}</caption>
                                    <caption class="text-center w-80p bg-grey-400">{{ date('d-m-Y', strtotime($key_date)) }}</caption>
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
                                            <th>Detalles de dolares en efectivo ($)</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_dollar_cash, 2) }}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['dolares'], 2) }}</td>
                                            <td 
                                                class="text-center {{  $differences[$key_user][$key_date]['dollar_cash'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['dollar_cash'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' ) }}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['dollar_cash'], 2) }}
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th>Dolares en físico ($)</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_dollar_denominations, 2) }}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['dolares'], 2)  }}</td>
                                            <td 
                                                class="text-center {{ $differences[$key_user][$key_date]['dollar_denominations'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['dollar_denominations'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' ) }}"
                                            >
                                                    {{  number_format($differences[$key_user][$key_date]['dollar_denominations'], 2) }}
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th>Bs en físico (Bs)</td>
                                            <td class="text-center">{{  number_format($cash_registers_totals[$key_user][$key_date][0]->total_bs_denominations, 2) }}</td>
                                            <td class="text-center">{{  number_format($saint_totals[$key_user][$key_date]['bolivares'], 2) }}</td>
                                            <td 
                                                class="text-center {{ ($differences[$key_user][$key_date]['bs_denominations'] > 0) 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['bs_denominations'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' )}}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['bs_denominations'], 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Punto de venta (Bs)</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_point_sale_bs, 2)  }}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['01']['bs'] 
                                                + $saint_totals[$key_user][$key_date]['02']['bs'], 2) }}</td>
                                            <td 
                                                class="text-center {{ $differences[$key_user][$key_date]['point_sale_bs'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['point_sale_bs'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' )}}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['point_sale_bs'], 2)  }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pago móvil y transferencias</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_pago_movil_bs, 2)  }}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['05']['bs'], 2) }}</td>
                                            <td 
                                                class="text-center {{$differences[$key_user][$key_date]['pago_movil_bs'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['pago_movil_bs'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' )}}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['pago_movil_bs'], 2)  }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>AMEX</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_amex, 2)  }}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['04']['bs'], 2) }}</td>
                                            <td 
                                                class="text-center {{$differences[$key_user][$key_date]['amex_bs'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['amex_bs'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' )}}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['amex_bs'], 2)  }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Todoticket</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_todoticket, 2)  }}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['03']['bs'], 2) }}</td>
                                            <td 
                                                class="text-center {{$differences[$key_user][$key_date]['todoticket_bs'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['todoticket_bs'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' )}}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['todoticket_bs'], 2)  }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Punto de venta int. ($)</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_point_sale_dollar, 2)}}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['08']['dollar'], 2)  }}</td>
                                            <td 
                                                class="text-center {{ $differences[$key_user][$key_date]['point_sale_dollar'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['point_sale_dollar'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' )}}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['point_sale_dollar'], 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Zelle</th>
                                            <td class="text-center">{{ number_format($cash_registers_totals[$key_user][$key_date][0]->total_zelle, 2)}}</td>
                                            <td class="text-center">{{ number_format($saint_totals[$key_user][$key_date]['07']['dollar'], 2) }}</td>
                                            <td 
                                                class="text-center {{ $differences[$key_user][$key_date]['zelle'] > 0 
                                                    ? 'text-blue-400' 
                                                    : ( $differences[$key_user][$key_date]['zelle'] < 0 
                                                        ? 'text-red-400' 
                                                        : '' )}}"
                                            >
                                                    {{ number_format($differences[$key_user][$key_date]['zelle'], 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="w-80p clearfix">
                                    <div class="w-40p left">
                                        <table class="w-full">
                                            <caption class="text-center bg-grey-400 w-full">Cantidad de billetes por denominación (Bolivares)</caption>
                                            <thead>
                                                <tr>
                                                    <th>Denominación</th>
                                                    <th>Cantidad</th>
                                                    <th>Subtotal (Bs)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($bs_denominations[$key_user][$key_date] as $record)
                                                    <tr>
                                                        <td class="text-center">{{ $record->denomination }}</td>
                                                        <td class="text-center">{{ $record->quantity }}</td>
                                                        <td class="text-center">{{ number_format($record->quantity * $record->denomination, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-grey-600">
                                                    <td>&nbsp;</td>
                                                    <td class="font-semibold">Total</td>
                                                    <td class="bg-grey-600 text-center">{{ number_format($totals_bs_denominations[$key_user][$key_date], 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="w-40p right">
                                        <table class="w-full">
                                            <caption class="text-center bg-grey-400 w-full">Cantidad de billetes por denominación (Dolares)</caption>
                                            <thead>
                                                <tr>
                                                    <th>Denominación</th>
                                                    <th>Cantidad</th>
                                                    <th>Total ($)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dollar_denominations[$key_user][$key_date] as $record)
                                                    <tr>
                                                        <td class="text-center">{{ $record->denomination }}</td>
                                                        <td class="text-center">{{ $record->quantity }}</td>
                                                        <td class="text-center">{{ number_format($record->quantity * $record->denomination, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-grey-600">
                                                    <td>&nbsp;</td>
                                                    <td class="font-semibold">Total</td>
                                                    <td class="text-center">{{ number_format($total_dollar_denominations[$key_user][$key_date], 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="page-break"></div>
                            @endif
                        @endforeach
                    @endif                               
                </div>
            @endforeach
            
            <div class="w-90p mb-8">
                <h1 class="text-center">Resumen de entrada de dinero en efectivo</h1>
            </div>
            <div class="w-80p clearfix">
                <div class="w-40p left">
                    <table class="w-full">
                        <caption class="text-center bg-grey-400 w-full">Cantidad de billetes por denominación (Bs)</caption>
                        <thead>
                            <tr>
                                <th>Denominación</th>
                                <th>Cantidad</th>
                                <th>Subtotal (Bs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($total_quantity_bs_denominations as $denomination => $quantity)
                                <tr>
                                    <td class="text-center">{{ $denomination }}</td>
                                    <td class="text-center">{{ $quantity }}</td>
                                    <td class="text-center">{{ number_format($quantity * floatval($denomination), 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-grey-600">
                                <td>&nbsp;</td>
                                <td class="font-semibold">Total</td>
                                <td class="bg-grey-600 text-center">{{ number_format($total_bs_denominations_summary, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="w-40p right">
                    <table class="w-full">
                        <caption class="text-center bg-grey-400 w-full">Cantidad de billetes por denominación ($)</caption>
                        <thead>
                            <tr>
                                <th>Denominación</th>
                                <th>Cantidad</th>
                                <th>Total ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($total_quantity_dollar_denominations as $denomination => $quantity)
                                <tr>
                                    <td class="text-center">{{ $denomination }}</td>
                                    <td class="text-center">{{ $quantity }}</td>
                                    <td class="text-center">{{ number_format($quantity * floatval($denomination), 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-grey-600">
                                <td>&nbsp;</td>
                                <td class="font-semibold">Total</td>
                                <td class="text-center">{{ number_format($total_dollar_denominations_summary, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script type="text/php">
            if (isset($pdf)) {
                $text = "page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
