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
                width: 1050px;
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
                    <p class="mb-2 font-semibold">Reporte de facturas Z</p>
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
            <div class="w-full"> 
               
                <table class="w-full mb-12">
                    <caption class="text-center w-full bg-grey-400 text-lg font-semibold">Resumen general</caption>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>Total ventas (Con I.V.A)</th>
                            <th>Base imponible</th>
                            <th>Alicuota<br/>16%</th>
                            <th>Base imponible</th>
                            <th>Alicuota<br/>8%</th>
                            <th>Venta del dia</th>
                            <th>Ventas<br/>De Licores</th>
                            <th>Ventas gravadas<br/>Viveres</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($totals_by_user as $key_codusua => $totals)
                            <tr>
                                <td>{{$key_codusua}}</td>
                                @foreach($totals as $total)
                                    <td>{{ number_format($total, 2) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="bg-grey-600">
                            <td class="font-semibold">Total:</td>
                            @foreach($total_general as $total)
                                <td class="text-center">{{ number_format($total, 2) }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <div class="page-break"></div>
               

                @foreach($totals_from_safact as $key_codusua => $dates)
                    <table class="w-full mb-12">
                        <caption class="text-center w-full bg-grey-400 text-lg font-semibold">{{ $key_codusua }}</caption>
                        <thead>
                            <tr>
                                <th>Fecha de la factura</th>
                                <th>Serial Fiscal</th>
                                <th>N.º de reporte "Z"</th>
                                <th>Cant. facturas</th>
                                <th>Último n.º factura</th>
                                <th>Total ventas (Con I.V.A)</th>
                                <th>Base imponible</th>
                                <th>Alicuota<br/>16%</th>
                                <th>Base imponible</th>
                                <th>Alicuota<br/>8%</th>
                                <th>Venta del dia</th>
                                <th>Ventas<br/>De Licores</th>
                                <th>Ventas gravadas<br/>Viveres</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dates as $key_date => $printers)
                                @foreach($printers as $key_printer => $z_numbers)
                                    @foreach($z_numbers as $key_z_number => $record)
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                            <td>{{ $key_printer }}</td>
                                            <td>{{ $key_z_number }}</td>
                                            <td>{{ $record->first()->nroFacturas }}</td>
                                            <td>{{ $record->first()->ultimoNroFactura }}</td>
                                            <td>{{ number_format($record->first()->ventaTotalIVA, 2) }}</td>
                                            @if (count($total_base_imponible_by_tax) > 0 && key_exists($key_codusua, $total_base_imponible_by_tax)
                                                    && key_exists($key_date, $total_base_imponible_by_tax[$key_codusua])
                                                        && key_exists($key_printer, $total_base_imponible_by_tax[$key_codusua][$key_date]))
                                                <td>{{ number_format($total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'], 2) }} </td>
                                                <td>{{ number_format($total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'] * 0.16, 2) }} </td>
                                                <td>{{ number_format($total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'], 2) }}</td>
                                                <td>{{ number_format($total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'] * 0.08, 2) }}</td>
                                            @else
                                                <td>0.00</td>
                                                <td>0.00</td>
                                                <td>0.00</td>
                                                <td>0.00</td>
                                            @endif
                                            <td>{{ number_format($record->first()->ventaTotalExenta, 2) }}</td>
                                            @if ($total_licores->count() > 0 && $total_licores->has($key_codusua)
                                                    && $total_licores[$key_codusua]->has($key_date) && $total_licores[$key_codusua][$key_date]->has($key_printer))
                                                <td>{{ number_format($total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS, 2) }}</td>
                                                <td>{{ number_format($record->first()->ventaTotalExenta - $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS, 2) }}</td>
                                            @else
                                                <td>0.00</td>
                                                <td>{{ number_format($record->first()->ventaTotalExenta, 2) }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                            <tr class="bg-grey-600">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                @foreach($totals_by_user[$key_codusua] as $total)
                                    <td class="text-center">{{ number_format($total, 2) }}</td>
                                @endforeach
                            </tr>
                        </tbody>
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
