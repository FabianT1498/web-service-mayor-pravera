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

            .bg-grey-800 {
                background-color: #5b5b5b;
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

            <div class="w-80p">
                <h1 class="text-center text-lg">Registros de Zelle</h1>
                @if ($zelle_records->count() > 0)
                    @foreach($zelle_records as $key_codusua => $dates)
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>Monto ($)</th>
                                    <th>Tasa de cambio (Bs)</th>
                                    <th>Bolivares</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="3" class="text-center bg-grey-800">{{ $key_codusua }}</th>
                                </tr>
                                @foreach($dates as $key_date => $records)
                                    <tr>
                                        <td colspan="3" class="text-center bg-grey-600">{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                    </tr>
                                    @foreach($records as $record)
                                        <tr>
                                            <td class="text-center">{{ number_format($record->amount, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                            <td class="text-center">{{ number_format($factors[$key_date]->first()->MaxFactor, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                            <td class="text-center">{{ number_format($record->amount * $factors[$key_date]->first()->MaxFactor, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td> 
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr class="bg-grey-800">
                                    <th class="text-center">{{ number_format($total_zelle_amount_by_user[$key_codusua]['dollar'], 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</th>
                                    <th>&nbsp;</th>
                                    <th class="text-center">{{ number_format($total_zelle_amount_by_user[$key_codusua]['bs'], 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="page-break"></div>      
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

            <div class="w-80p">
                <h1 class="text-center text-lg">Registros de Zelle de SAINT</h1>
                @if ($zelle_records_from_saint->count() > 0)
                    @foreach($zelle_records_from_saint as $key_codusua => $dates)
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>Monto ($)</th>
                                    <th>Tasa de cambio</th>
                                    <th>Bolivares</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="4" class="text-center bg-grey-800">{{ $key_codusua }}</th>
                                </tr>
                                @foreach($dates as $key_date => $records)
                                    <tr>
                                        <td colspan="4" class="text-center bg-grey-600">{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                    </tr>
                                    @foreach($records as $record)
                                        <tr>
                                            <td  class="text-center">{{ number_format($record->MontoDiv, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                            <td  class="text-center">{{ number_format($record->FactorV, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                            <td  class="text-center">{{ number_format($record->Monto, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                            <td  class="text-center">{{ $record->TitularCta }}</td>   
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr class="bg-grey-800">
                                    <th class="text-center">{{ $total_zelle_amount_by_user_from_saint[$key_codusua]->first()->MontoDiv . " " . config("constants.CURRENCY_SIGNS.dollar") }}</th>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center">{{ $total_zelle_amount_by_user_from_saint[$key_codusua]->first()->Monto . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</th>
                                    <th class="text-center">&nbsp;</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="page-break"></div>        
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

            <div class="w-80p">
                <h1 class="text-center text-lg">Registros de punto de venta internacional</h1>
                @if ($point_sale_dollar_records->count() > 0)
                    @foreach($point_sale_dollar_records as $key_codusua => $dates)
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>Monto ($)</th>
                                    <th>Tasa de cambio (Bs)</th>
                                    <th>Bolivares</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="3" class="text-center bg-grey-800">{{ $key_codusua }}</th>
                                </tr>
                                @foreach($dates as $key_date => $records)
                                    <tr>
                                        <td colspan="3" class="text-center bg-grey-600">{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                    </tr>
                                    @foreach($records as $record)
                                        <tr>
                                            <td class="text-center">{{ number_format($record->amount, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                            <td class="text-center">{{ number_format($factors[$key_date]->first()->MaxFactor, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                            <td class="text-center">{{ number_format($record->amount * $factors[$key_date]->first()->MaxFactor, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td> 
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr class="bg-grey-800">
                                    <th class="text-center">{{ number_format($total_point_sale_dollar_amount_by_user[$key_codusua]['dollar'], 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</th>
                                    <th>&nbsp;</th>
                                    <th class="text-center">{{ number_format($total_point_sale_dollar_amount_by_user[$key_codusua]['bs'], 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="page-break"></div>      
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

            <div class="w-80p">
                <h1 class="text-center text-lg">Registros de Punto de venta internacional de SAINT</h1>
                @if ($point_sale_dollar_records_saint->count() > 0)
                    @foreach($point_sale_dollar_records_saint as $key_codusua => $dates)
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>Monto ($)</th>
                                    <th>Tasa de cambio</th>
                                    <th>Bolivares</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th colspan="4" class="text-center bg-grey-800">{{ $key_codusua }}</th>
                                </tr>
                                @foreach($dates as $key_date => $records)
                                    <tr>
                                        <td colspan="4" class="text-center bg-grey-600">{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                    </tr>
                                    @foreach($records as $record)
                                        <tr>
                                            <td  class="text-center">{{ number_format($record->MontoDiv, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                            <td  class="text-center">{{ number_format($record->FactorV, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                            <td  class="text-center">{{ number_format($record->Monto, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                            <td  class="text-center">{{ $record->TitularCta }}</td>   
                                        </tr>
                                    @endforeach
                                @endforeach
                                <tr class="bg-grey-800">
                                    <th class="text-center">{{ $total_point_sale_dollar_amount_by_user_saint[$key_codusua]->first()->MontoDiv . " " . config("constants.CURRENCY_SIGNS.dollar") }}</th>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center">{{ $total_point_sale_dollar_amount_by_user_saint[$key_codusua]->first()->Monto . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</th>
                                    <th class="text-center">&nbsp;</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="page-break"></div>        
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
