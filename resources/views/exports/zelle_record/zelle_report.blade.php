@foreach($zelle_records as $key_codusua => $dates)
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
                <td>&nbsp;</td>
            </tr>
            @foreach($dates as $key_date => $records)
                <tr>
                    <td>{{ $key_date }}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @foreach($records as $record)
                    <tr>
                        <td>{{ $record->amount }}</td>
                        <td>{{ $record->amount }}</td>
                        <td>{{ $record->amount }}</td>
                        <td>{{ $record->amount }}</td>
                        <td>&nbsp;</td>    
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>      
@endforeach