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

            .page-break {
                page-break-after: always;
            }

            table {
                display: block;
                border-collapse: collapse;
                text-align: center;
	            vertical-align: middle;
                border: 1px solid black;
                display: inline-table;
                caption-side: top;
                table-layout: fixed;
            }

            caption {
                display: table-caption;
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

            th, td {
                border: 1px solid #ccc;
                overflow: hidden;
                width: 120px;
                padding: 2px 2px;
            }

            td {
                height: 20px;
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
                font-size: 1.5rem;
            }

            .total-width-text {
                font-weight: 500;
            }

            .font-semibold {
                font-weight: 400;
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

            .text-right {
                text-align: right;
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
                    <p class="mb-2 font-semibold">Reporte de vueltos por factura</p>
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
            <div class="w-90p mb-8 mx-auto"> 
                <table class="w-90p">
                    <caption class="text-center w-90p bg-grey-400 text-lg font-semibold">Resumen general</caption>
                    <thead>
                        <tr>
                            <th>Caja</th>
                            <th>Vuelto Efec.(Bs)</th>
                            <th>Vuelto Efec.($)</th>
                            <th>Vuelto PM.(Bs)</th>
                            <th>Vuelto PM.($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($total_bill_vales_vueltos_by_user as $key_codusua => $metodos_vuelto)
                            <tr>
                                <td>{{ $key_codusua }}</td>
                                @if($metodos_vuelto->has('Efectivo'))
                                    <td>{{ number_format($metodos_vuelto['Efectivo']->first()->MontoBs, 2) }}</td>
                                    <td>{{ number_format($metodos_vuelto['Efectivo']->first()->MontoDiv, 2) }}</td>
                                @else
                                    <td>0.00</td>
                                    <td>0.00</td>
                                @endif
                                @if($metodos_vuelto->has('PM'))
                                    <td>{{ number_format($metodos_vuelto['PM']->first()->MontoBs, 2) }}</td>
                                    <td>{{ number_format($metodos_vuelto['PM']->first()->MontoDiv, 2) }}</td>
                                @else
                                    <td>0.00</td>
                                    <td>0.00</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr class="bg-grey-600" >
                            <td class="total-width-text">Total</td>
                            <td class="total-width-text">{{ number_format(array_key_exists('Efectivo', $total_bill_vueltos) ? $total_bill_vueltos['Efectivo']['MontoBs'] : 0, 2) }}</td>
                            <td class="total-width-text">{{ number_format( array_key_exists('Efectivo', $total_bill_vueltos) ? $total_bill_vueltos['Efectivo']['MontoDiv'] : 0, 2) }}</td>
                            <td class="total-width-text">{{ number_format(array_key_exists('PM', $total_bill_vueltos) ? $total_bill_vueltos['PM']['MontoBs'] : 0, 2) }}</td>
                            <td class="total-width-text">{{ number_format( array_key_exists('PM', $total_bill_vueltos) ? $total_bill_vueltos['PM']['MontoDiv'] : 0, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="page-break"></div>

                @foreach($bill_vueltos as $key_codusua => $dates)
                    <table class="w-90p">
                        <caption class="text-center w-90p bg-grey-400 text-lg font-semibold">{{ $key_codusua }}</caption>
                        @foreach($dates as $key_date => $numerosD)
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Numero Factura</th>
                                    <th>Tasa(Bs)</th>
                                    <th>Vuelto Efec.($)</th>
                                    <th>Vuelto Efec.(Bs)</th>
                                    <th>Vuelto PM.($)</th>
                                    <th>Vuelto PM.(Bs)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($numerosD as $key_numero_d => $metodos_vueltos)
                                    <tr>
                                        <td>{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                        <td>{{ $key_numero_d }}</td>
                                        <td>{{ number_format($metodos_vueltos->first()->first()->Factor, 2) }}</td>
                                        @if ($metodos_vueltos->has('Efectivo'))
                                            <td>{{ number_format($metodos_vueltos['Efectivo']->first()->MontoDiv, 2) }}</td>
                                            <td>{{ number_format($metodos_vueltos['Efectivo']->first()->MontoBs, 2) }}</td>
                                        @else
                                            <td>0.00</td>
                                            <td>0.00</td>
                                        @endif

                                        @if ($metodos_vueltos->has('PM'))
                                            <td>{{ number_format($metodos_vueltos['PM']->first()->MontoDiv, 2) }}</td>
                                            <td>{{ number_format($metodos_vueltos['PM']->first()->MontoBs, 2) }}</td>
                                        @else
                                            <td>0.00</td>
                                            <td>0.00</td>
                                        @endif    
                                    </tr>
                                @endforeach
                            </tbody>
                        @endforeach
                        <tfoot>
                            <tr class="bg-grey-400 ">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="total-width-text">Total: </td>
                                @if ($total_bill_vales_vueltos_by_user[$key_codusua]->has('Efectivo'))
                                    <td class="total-width-text">{{ number_format($total_bill_vales_vueltos_by_user[$key_codusua]['Efectivo']->first()->MontoDiv, 2) }}</td>
                                    <td class="total-width-text">{{ number_format($total_bill_vales_vueltos_by_user[$key_codusua]['Efectivo']->first()->MontoBs, 2) }}</td>
                                @else
                                    <td>0.00</td>
                                    <td>0.00</td>
                                @endif
                                @if ($total_bill_vales_vueltos_by_user[$key_codusua]->has('PM'))
                                    <td class="total-width-text">{{ number_format($total_bill_vales_vueltos_by_user[$key_codusua]['PM']->first()->MontoDiv, 2) }}</td>
                                    <td class="total-width-text">{{ number_format($total_bill_vales_vueltos_by_user[$key_codusua]['PM']->first()->MontoBs, 2) }}</td>
                                @else
                                    <td>0.00</td>
                                    <td>0.00</td>
                                @endif                            
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="page-break"></div>
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
