@if ($point_sale_dollar_records->count() > 0)
    @foreach($point_sale_dollar_records as $key_codusua => $dates)
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
                    <td>{{ $total_point_sale_dollar_amount_by_user[$key_codusua]['dollar'] }}</td>
                    <td>&nbsp;</td>
                    <td>{{ $total_point_sale_dollar_amount_by_user[$key_codusua]['bs'] }}</td>
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