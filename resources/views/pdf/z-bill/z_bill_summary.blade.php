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
            <div> 
                @foreach($total_exento as $key_codusua => $dates)
                    <table class="w-80p mb-12">
                        <caption class="text-center w-80p bg-grey-400">$key_codusua</caption>
                        <thead>
                            <tr>
                                <th>Fecha de la factura</th>
                                <th>Serial Fiscal</th>
                                <th>N◦ de reporte "Z"</th>
                                <th>Total ventas (Con I.V.A)</th>
                                <th>Base imponible</th>
                                <th>
                                    <td>Alicuota</td>
                                    <td>16%</td>
                                </th>
                                <th>Base imponible</th>
                                <th>
                                    <td>Alicuota</td>
                                    <td>8%</td>
                                </th>
                                <th>Venta del dia</th>
                                <th>
                                    <td>Ventas Gravadas</td>
                                    <td>DE LICORES</td>
                                </th>
                                <th>
                                    <td>Ventas Gravadas</td>
                                    <td>VIVERES</td>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dates as $key_date => $printers)
                                @foreach($printers as $key_printer => $z_numbers)
                                    @foreach($z_numbers as $key_z_number => $record)
                                        <td>{{ $key_date }}</td>
                                        <td>{{ $key_printer }}</td>
                                        <td>{{ $key_z_number }}</td>
                                        <td>0</td>
                                        <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'] }}</td>
                                        <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'] * 0.16 }}</td>
                                        <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'] }}</td>
                                        <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'] * 0.08 }}</td>
                                        <td>{{ $record->first()->ventaTotalExenta }}</td>
                                        <td>{{ 
                                            $total_licores->has($key_codusua) 
                                                ? $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS
                                                : 0.00
                                        }}
                                        </td>
                                        <td>{{ $record->first()->ventaTotalExenta - ($total_licores->has($key_codusua) 
                                            ? $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS
                                            : 0.00)
                                        }}</td>
                                    @endforeach
                                @endforeach
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
