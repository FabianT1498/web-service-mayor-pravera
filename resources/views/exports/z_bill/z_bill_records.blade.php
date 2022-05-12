<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <table class="w-full mb-12">
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
                @foreach($totals_from_safact as $key_codusua => $dates)
                    <tr>
                        <td>{{ $key_codusua }}</td>
                    </tr>
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
                @endforeach
            </tbody>
        </table>      
    </body>
</html>
