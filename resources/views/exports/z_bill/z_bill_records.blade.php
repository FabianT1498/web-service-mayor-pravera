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
                    @foreach($z_numbers as $key_z_number => $records)
                        @foreach($records as $record)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($key_date)) }}</td>
                                <td>{{ $key_printer }}</td>
                                <td>{{ $key_z_number }}</td>
                                <td>{{ $record->nroFacturas }}</td>
                                <td>{{ $record->ultimoNroFactura }}</td>
                                <td>{{ $record->ventaTotalIVA }}</td>
                                @if (count($total_base_imponible_by_tax) > 0 && key_exists($key_codusua, $total_base_imponible_by_tax)
                                        && key_exists($key_date, $total_base_imponible_by_tax[$key_codusua])
                                            && key_exists($key_printer, $total_base_imponible_by_tax[$key_codusua][$key_date])
                                                    && key_exists($record->TipoFac, $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number]))
                                    <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number][$record->TipoFac]['IVA'] }} </td>
                                    <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number][$record->TipoFac]['IVA'] * 0.16 }} </td>
                                    <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number][$record->TipoFac]['IVA8'] }}</td>
                                    <td>{{ $total_base_imponible_by_tax[$key_codusua][$key_date][$key_printer][$key_z_number][$record->TipoFac]['IVA8'] * 0.08 }}</td>
                                @else
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                @endif
                                <td>{{ $record->ventaTotalExenta }}</td>
                                @if ($total_licores->count() > 0 && $total_licores->has($key_codusua)
                                        && $total_licores[$key_codusua]->has($key_date) && $total_licores[$key_codusua][$key_date]->has($key_printer)
                                        && $total_licores[$key_codusua][$key_date][$key_printer]->has($key_z_number)
                                        && $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number]->has($record->TipoFac))
                                    <td>{{ $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number][$record->TipoFac]->first()->ventaLicoresBS }}</td>
                                    <td>{{ $record->ventaTotalExenta - $total_licores[$key_codusua][$key_date][$key_printer][$key_z_number][$record->TipoFac]->first()->ventaLicoresBS }}</td>
                                @else
                                    <td>0.00</td>
                                    <td>{{ $record->ventaTotalExenta }}</td>
                                @endif
                            </tr>
                        @endforeach
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
