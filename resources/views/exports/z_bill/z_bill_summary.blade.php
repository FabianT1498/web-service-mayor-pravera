<table class="w-full mb-12">
    <thead>
        <tr>
            <th>&nbsp;</th>
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
        @foreach($totals_by_user as $key_codusua => $totals)
            <tr>
                <td>{{$key_codusua}}</td>
                @foreach($totals as $total)
                    <td>{{ $total }}</td>
                @endforeach
            </tr>
        @endforeach
        <tr class="bg-grey-600">
            <td class="font-semibold">Total:</td>
            @foreach($total_general as $total)
                <td class="text-center">{{ $total }}</td>
            @endforeach
        </tr>
    </tbody>
</table>