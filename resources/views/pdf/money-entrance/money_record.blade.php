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


            .w-30p{
                width: 30%;
            }

            .w-70p{
                width: 70%;
            }

            .w-80p{
                width: 80%;
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

            .w-full{
                width: 100%;
            }
            
            .pr-10{
                margin-right: 5rem;
            }

            .text-center {
                text-align: center;
            }

        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="container">
            <div class="w-full mb-4 clearfix">
                <div class="left">Logo</div>
                <div class="right pr-10">
                    <p class="mb-2 font-semibold">Entrada de dinero</p>
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
                 
                    <table class="w-80p mb-8">
                        <caption class="text-center w-80p bg-grey-400">Total de efectivo por caja</caption>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Bolivares</th>
                                <th>Dolares</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totals_cash_by_user as $key => $entries)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="text-center">{{ $entries['bolivar'] . ' ' . $currency_signs['bs'] }}</td>
                                    <td class="text-center">{{ $entries['dollar'] . ' ' . $currency_signs['dollar'] }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="font-semibold">Total</td>
                                <td class="text-center">{{ $totals_cash_by_interval['bolivar'] . ' ' . $currency_signs['bs']  }}</td>
                                <td class="text-center">{{ $totals_cash_by_interval['dollar'] . ' ' . $currency_signs['dollar']  }}</td>
                            </tr>
                        </tbody>
                    </table>
                
                </div>

                <div>
                 
                    <table class="w-80p mb-8">
                        <caption class="text-center w-80p bg-grey-400">Total en métodos de pagos electronicos por caja</caption>
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>PV. Debito (Bs)</th>
                                <th>PV. Credito(Bs)</th>
                                <th>TDC (Bs)</th>
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
                                    @foreach($entries as $subtotal)
                                        <td class="text-center">{{ $subtotal }}</td>
                                    @endforeach                              
                                </tr>
                            @endforeach
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
