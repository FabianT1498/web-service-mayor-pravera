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
                font-size: 1.5rem;
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
                    <p class="mb-2 font-semibold">Registros de entradas por Zelle</p>
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

            <h1 class="text-center text-lg">Entradas de dinero del sistema</h1>
            @if ($zelle_records->count() > 0)
                @foreach($zelle_records as $key_codusua => $dates)
                    <table>
                        <thead>
                            <tr>
                                <th>Monto ($)</th>
                                <th>Tipo de cambio</th>
                                <th>Bolivares</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $key_codusua }}</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            @foreach($dates as $key_date => $records)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @foreach($records as $record)
                                    <tr>
                                        <td>{{ $record->amount }}</td>
                                        <td>{{ $factors[$key_date]->first()->MaxFactor }}</td>
                                        <td>{{ number_format($record->amount * $factors[$key_date]->first()->MaxFactor, 2) }}</td>
                                        <td>{{ '' }}</td>   
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td>{{ $total_zelle_amount_by_user[$key_codusua]['dollar'] }}</td>
                                <td>&nbsp;</td>
                                <td>{{ $total_zelle_amount_by_user[$key_codusua]['bs'] }}</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>      
                @endforeach
            @else
                <table>
                    <tbody>
                        <tr>
                            <td>No hay Zelle's disponibles para este día</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            @endif
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
