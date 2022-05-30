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
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        @if($money_back_by_users[$key_codusua][$key_date]->has('Efectivo'))
                            <td>{{ number_format($money_back_by_users[$key_codusua][$key_date]['Efectivo']->first()->MontoDiv, 2) }}</td>
                            <td>{{ number_format($money_back_by_users[$key_codusua][$key_date]['Efectivo']->first()->MontoBs, 2) }}</td>
                        @else
                            <td>0.00</td>
                            <td>0.00</td>
                        @endif
                        @if($money_back_by_users[$key_codusua][$key_date]->has('PM'))
                            <td>{{ number_format($money_back_by_users[$key_codusua][$key_date]['PM']->first()->MontoDiv, 2) }}</td>
                            <td>{{ number_format($money_back_by_users[$key_codusua][$key_date]['PM']->first()->MontoBs, 2) }}</td>
                        @else
                            <td>0.00</td>
                            <td>0.00</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td>Total: </td>
                    @if(array_key_exists('Efectivo', $total_money_back_by_users[$key_codusua]))
                        <td>{{ number_format($total_money_back_by_users[$key_codusua]['Efectivo']['MontoDiv'], 2) }}</td>
                        <td>{{ number_format($total_money_back_by_users[$key_codusua]['Efectivo']['MontoBs'], 2) }}</td>
                    @else
                        <td>0.00</td>
                        <td>0.00</td>
                    @endif
                    @if(array_key_exists('PM', $total_money_back_by_users[$key_codusua]))
                        <td>{{ number_format($total_money_back_by_users[$key_codusua]['PM']['MontoDiv'], 2) }}</td>
                        <td>{{ number_format($total_money_back_by_users[$key_codusua]['PM']['MontoBs'], 2) }}</td>
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
