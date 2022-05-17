@if ($bill_vueltos->count() > 0)
    @foreach($bill_vueltos as $key_codusua => $dates)
        <table>
            <thead>
                <tr>
                    <th>Numero Factura</th>
                    <th>Vuelto ($)</th>
                    <th>Tasa</th>
                    <th>Vuelto (Bs)</th>
                </tr>
                <tr>
                    <td>{{ $key_codusua }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody>
                @foreach($dates as $key_date => $records)
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($key_date)) }}</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    @foreach($records as $record)
                        <tr>
                            <td>{{ $record->NumeroD }}</td>
                            <td>{{ number_format($record->MontoDiv, 2) }}</td>
                            <td>{{ number_format($record->Factor, 2) }}</td>
                            <td>{{ number_format($record->MontoBs, 2) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-grey-400 ">
                    <td class="total-width-text">Total: </td>
                    <td class="total-width-text">{{ number_format($total_bill_vales_vueltos_by_user[$key_codusua]->first()->MontoDiv, 2) }}</td>
                    <td>&nbsp;</td>
                    <td class="total-width-text">{{ number_format($total_bill_vales_vueltos_by_user[$key_codusua]->first()->MontoBs, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @endforeach
@else
    <table>
        <tbody>
            <td>No hay facturas para el día de hoy</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tbody>
    </table>
@endif
