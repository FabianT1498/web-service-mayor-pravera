@if ($bill_vueltos->count() > 0)
    @foreach($bill_vueltos as $key_codusua => $dates)
        <table>
            <thead>
                <tr>
                    <th>Numero Factura</th>
                    <th>Tasa(Bs)</th>
                    <th>Vuelto Efec.($)</th>
                    <th>Vuelto Efec.(Bs)</th>
                    <th>Vuelto PM.($)</th>
                    <th>Vuelto PM.(Bs)</th>
                </tr>
                <tr>
                    <td>{{ $key_codusua }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody>
                @foreach($dates as $key_date => $numerosD)
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($key_date)) }}</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    @foreach($numerosD as $key_numero_d => $record)
                        <tr>
                            <td>{{ $key_numero_d }}</td>
                            <td>{{ number_format($record->first()->Factor, 2) }}</td>
                            <td>{{ number_format($record->first()->MontoDivEfect, 2) }}</td>
                            <td>{{ number_format($record->first()->MontoBsEfect, 2) }}</td>
                            <td>{{ number_format($record->first()->MontoDivPM, 2) }}</td>
                            <td>{{ number_format($record->first()->MontoBsPM, 2) }}</td> 
                        </tr>
                    @endforeach
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>{{ number_format($bill_vueltos_by_user_date[$key_codusua][$key_date]->first()->MontoDivEfect, 2) }}</td>
                        <td>{{ number_format($bill_vueltos_by_user_date[$key_codusua][$key_date]->first()->MontoBsEfect, 2) }}</td>
                        <td>{{ number_format($bill_vueltos_by_user_date[$key_codusua][$key_date]->first()->MontoDivPM, 2) }}</td>
                        <td>{{ number_format($bill_vueltos_by_user_date[$key_codusua][$key_date]->first()->MontoBsPM, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-grey-400">
                    <td>&nbsp;</td>
                    <td>Total: </td>
                    <td>{{ number_format($total_vuelto_by_user[$key_codusua]['MontoDivEfect'], 2) }}</td>
                    <td>{{ number_format($total_vuelto_by_user[$key_codusua]['MontoBsEfect'], 2) }}</td>
                    <td>{{ number_format($total_vuelto_by_user[$key_codusua]['MontoDivPM'], 2) }}</td>
                    <td>{{ number_format($total_vuelto_by_user[$key_codusua]['MontoBsPM'], 2) }}</td>      
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
