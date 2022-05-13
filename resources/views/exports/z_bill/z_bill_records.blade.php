<table>
    <thead>
        <tr>
            <th>Fecha<br/>factura</th>
            <th>Serial Fiscal</th>
            <th>N.º reporte<br/>"Z"</th>
            <th>Cant.<br/>facturas</th>
            <th>Último<br/>n.º factura</th>
            <th>Total ventas<br/>(Con I.V.A)</th>
            <th>Base<br/>imponible</th>
            <th>Alicuota<br/>16%</th>
            <th>Base<br/>imponible</th>
            <th>Alicuota<br/>8%</th>
            <th>Venta<br/>del dia</th>
            <th>Ventas<br/>Licores</th>
            <th>Ventas gravadas<br/>Viveres</th>
        </tr>
    </thead>
    <tbody>
        @foreach($totals_from_safact as $key_codusua => $dates)
            <tr>
                <td>{{ $key_codusua }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            @foreach($dates as $key_date => $printers)
                @foreach($printers as $key_printer => $z_numbers)
                    @foreach($z_numbers as $key_z_number => $record)
                        <tr>
                            <td>{{ $key_date }}</td>
                            <td>{{ $key_printer }}</td>
                            <td>{{ $key_z_number }}</td>
                            <td>{{ $record->first()->nroFacturas }}</td>
                            <td>{{ $record->first()->ultimoNroFactura }}</td>
                            <td>{{ $record->first()->ventaTotalIVA }}</td>
                            @if (count($total_base_imponible_by_tax) > 0 && key_exists($key_codusua, $total_base_imponible_by_tax)
                                    && key_exists($key_date, $total_base_imponible_by_tax[$key_codusua])
                                        && key_exists($key_printer, $total_base_imponible_by_tax[$key_codusua][$key_date]))
                                <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'] }} </td>
                                <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA'] * 0.16 }} </td>
                                <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'] }}</td>
                                <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]['IVA8'] * 0.08 }}</td>
                            @else
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            @endif
                            <td>{{ $record->first()->ventaTotalExenta }}</td>
                            @if ($total_licores->count() > 0 && $total_licores->has($key_codusua)
                                    && $total_licores[$key_codusua]->has($key_date) && $total_licores[$key_codusua][$key_date]->has($key_printer))
                                <td>{{ $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS }}</td>
                                <td>{{ $record->first()->ventaTotalExenta - $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->first()->ventaLicoresBS }}</td>
                            @else
                                <td>0.00</td>
                                <td>{{ $record->first()->ventaTotalExenta }}</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                @foreach($totals_by_user[$key_codusua] as $total)
                    <td>{{ $total }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>      
