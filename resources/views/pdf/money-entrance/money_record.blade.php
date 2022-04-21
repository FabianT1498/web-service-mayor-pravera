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
                display: inline-block;
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

            .w-70p{
                width: 70%;
            }

            .w-80p{
                width: 80%;
            }

            .w-90p{
                width: 90%;
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
            <div class="w-full mb-4 clearfix">
                <div class="left">Logo</div>
                <div class="right pr-10">
                    <p class="mb-2 font-semibold">Reporte de entrada de dinero</p>
                    <p>
                        @if ($start_date === $end_date)
                            Fecha: {{ $start_date }}
                        @else
                            Reporte por intervalo: {{ $start_date }}<br>
                            {{ " Hasta " . $end_date }}
                        @endif
                    </p>
                </div>
            </div>
            <div>
                <div>
                    
                    <table class="w-80p mb-12">
                        <caption class="text-center w-80p bg-grey-400">Entrada total</caption>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Subtotal</th>
                                <th>Porcentaje (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Entrada en dolares
                                <td class="text-center">{{ number_format($total_dollars, 2) }}</td>
                                <td class="text-center">{{ number_format($total_dollars/$total, 2) * 100 }}&nbsp;%</td>
                            </tr>
                            <tr>
                                <td>Entrada en bolívares ($)</td>
                                <td class="text-center">{{ number_format($total_bs_to_dollars, 2) }}</td>
                                <td class="text-center">{{ number_format($total_bs_to_dollars/$total, 2) * 100 }}&nbsp;%</td>
                            </tr>
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total</td>
                                <td class="text-center">{{ number_format($total, 2) }}</td>
                                <td class="text-center">100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    
                    <table class="w-80p mb-12">
                        <caption class="text-center w-80p bg-grey-400">IVA</caption>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Subtotal (Bs)</th>
                                <th>Subtotal ($)</th>
                                <th>Porcentaje (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>I.V.A Fac ($)</td>
                                <td class="text-center">{{ number_format($totals_iva[0][0]->iva, 2) }}</td>
                                <td class="text-center">{{ number_format($totals_iva[0][0]->ivaDolares, 2) }}</td>
                                <td class="text-center">{{ number_format($totals_iva[0][0]->ivaDolares/$total_iva_dollar, 2) * 100 }}&nbsp;%</td>
                            </tr>
                            <tr>
                                <td>I.V.A NT($)</td>
                                <td class="text-center">{{ number_format($totals_iva[1][0]->iva, 2) }}</td>
                                <td class="text-center">{{ number_format($totals_iva[1][0]->ivaDolares, 2) }}</td>
                                <td class="text-center">{{ number_format($totals_iva[1][0]->ivaDolares/$total_iva_dollar, 2) * 100 }}&nbsp;%</td>
                            </tr>
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total</td>
                                <td class="text-center">{{ number_format($total_iva_bs, 2) }}</td>
                                <td class="text-center">{{ number_format($total_iva_dollar, 2) }}</td>
                                <td class="text-center">100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                 
                    <table class="w-80p mb-12">
                        <caption class="text-center w-80p bg-grey-400">Resumen del total de dinero tangible por caja</caption>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Bolívares (Tangibles)</th>
                                <th>Dólares (Tangibles)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totals_safact_by_user as $key => $entries)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="text-center">{{ number_format($entries['bolivar'], 2) }}</td>
                                    <td class="text-center">{{ number_format($entries['dollar'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total (Bs)</td>
                                <td class="text-center">{{ number_format($totals_safact_by_interval['bolivar'], 2) }}</td>
                                <td class="text-center text-lg">&mdash;</td>
                            </tr>
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total ($)</td>
                                <td class="text-center">{{ number_format($totals_safact_by_interval['bolivarToDollar'], 2) }}</td>
                                <td class="text-center">{{ number_format($totals_safact_by_interval['dollar'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                
                </div>

                <div>
                    <table class="w-90p mb-12">
                        <caption class="text-center w-90p bg-grey-400">Resumen del total en métodos de pagos electrónicos por caja</caption>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>PV. Debito (Bs)</th>
                                <th>TDC (Bs)</th>
                                <th>Todoticket (Bs)</th>
                                <th>AMEX (Bs)</th>
                                <th>Transferencias (Bs)</th>
                                <th>Zelle ($)</th>
                                <th>PV Int. ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totals_e_payment_by_user as $key => $entries)
                                <tr>
                                    <td>{{ $key }}</td>
                                    @foreach($entries as $codPago => $currencies)
                                        @if ($codPago === '07' || $codPago === '08')
                                            <td class="text-center">{{ number_format($currencies['dollar'], 2) }}</td>
                                        @else
                                            <td class="text-center">{{ number_format($currencies['bs'], 2) }}</td>
                                        @endif
                                    @endforeach                              
                                </tr>
                            @endforeach
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total (Bs)</td>
                                @foreach($totals_e_payment_by_interval as $codPago => $currencies)
                                    @if ($codPago === '07' || $codPago === '08')
                                        <td class="text-center text-lg">&mdash;</td>
                                    @else
                                        <td class="text-center">{{ number_format($currencies['bs'], 2) }}</td>
                                    @endif
                                @endforeach
                            </tr>
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total ($)</td>
                                @foreach($totals_e_payment_by_interval as $codPago => $currencies)
                                    <td class="text-center">{{ number_format($currencies['dollar'], 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <table class="w-90p mb-8">
                        <caption class="text-center w-90p bg-grey-400">Resumen del total de entrada de credito por caja</caption>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Total (Bs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totals_safact_by_user as $key => $entries)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="text-center">{{ number_format($entries['credito'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total (Bs)</td>
                                <td class="text-center">{{ number_format($totals_safact_by_interval['credito'], 2)  }}</td>                         
                            </tr>
                            <tr class="bg-grey-600">
                                <td class="font-semibold">Total ($)</td>
                                <td class="text-center">{{ number_format($totals_safact_by_interval['creditoToDollar'], 2)  }}</td>                         
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="w-80p mb-8">
                    <h1 class="text-center">Totales de entradas en dinero tangible por fecha</h1>
                </div>

                @foreach($totals_safact as $key_user => $dates)
                    
                        <table class="w-80p mb-4">
                            <caption class="text-center w-80p bg-grey-400">{{ $key_user }}</caption>
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Bolívares (Tangibles)</th>
                                    <th>Dólares (Tangibles)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dates as $key_date => $records)
                                    @foreach($records as $record)
                                        <tr>
                                            <td class="text-center font-semibold">{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                            <td class="text-center">{{ number_format($record->bolivares, 2) }}</td>
                                            <td class="text-center">{{ number_format($record->dolares, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                @endforeach

                <div class="w-80p mb-8">
                    <h1 class="text-center">Total de entrada de credito por fecha</h1>
                </div>

                @foreach($totals_safact as $key_user => $dates)
                    <table class="w-80p mb-4">
                        <caption class="text-center w-80p bg-grey-400">{{ $key_user }}</caption>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Total (Bs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dates as $key_date => $records)
                                @foreach($records as $record)
                                        <tr>
                                            <td class="text-center font-semibold">{{ date('d-m-Y', strtotime($key_date)) }}</caption>
                                            <td class="text-center">{{ number_format($record->credito, 2) . ' ' . $currency_signs['bs'] }}</td>
                                        </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                @endforeach

                <div class="w-90p mb-8">
                    <h1 class="text-center">Totales de entradas en métodos de pago electrónicos</h1>
                </div>
                @foreach($totals_e_payment as $key_user => $dates)
                   
                        <table class="w-90p mb-4">
                            <caption class="text-center w-90p bg-grey-400">{{ $key_user }}</caption>

                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>PV. Debito (Bs)</th>
                                    <th>TDC (Bs)</th>
                                    <th>Todoticket (Bs)</th>
                                    <th>AMEX (Bs)</th>
                                    <th>Transferencias (Bs)</th>
                                    <th>Zelle ($)</th>
                                    <th>PV Int. ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dates as $key_date => $date_record)
                                    <tr>
                                        <td class="text-center font-semibold">{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                        <td class="text-center">{{ number_format($date_record['01']['bs'], 2) }}</td>
                                        <td class="text-center">{{ number_format($date_record['02']['bs'], 2) }}</td>
                                        <td class="text-center">{{ number_format($date_record['03']['bs'], 2) }}</td>
                                        <td class="text-center">{{ number_format($date_record['04']['bs'], 2) }}</td>
                                        <td class="text-center">{{ number_format($date_record['05']['bs'], 2) }}</td>
                                        <td class="text-center">{{ number_format($date_record['07']['dollar'], 2) }}</td>
                                        <td class="text-center">{{ number_format($date_record['08']['dollar'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    
                @endforeach
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
