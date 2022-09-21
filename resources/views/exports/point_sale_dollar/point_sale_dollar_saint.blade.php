@if ($point_sale_dollar_records_saint->count() > 0)
    @foreach($point_sale_dollar_records_saint as $key_codusua => $dates)
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
                            <td>{{ number_format($record->MontoDiv, 2) }}</td>
                            <td>{{ $record->FactorV }}</td>
                            <td>{{ number_format($record->Monto, 2) }}</td>
                            <td>{{ $record->TitularCta }}</td>   
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td>{{ $total_point_sale_dollar_amount_by_user_saint[$key_codusua]->first()->MontoDiv }}</td>
                    <td>&nbsp;</td>
                    <td>{{ $total_point_sale_dollar_amount_by_user_saint[$key_codusua]->first()->Monto }}</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>      
    @endforeach
@else
    <table>
        <tbody>
            <tr>
                <td>No hay registros de punto de venta internacional disponibles para este día</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
@endif