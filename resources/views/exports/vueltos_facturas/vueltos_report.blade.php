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
                    @foreach($numerosD as $key_numero_d => $metodos_vueltos)
                        <tr>
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
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-grey-400 ">
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
