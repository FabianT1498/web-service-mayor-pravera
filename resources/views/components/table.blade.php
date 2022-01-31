@props(['columns' => [], 'data' => [], 'total' => 0, 'input_value' => 0 ])
<table class="table table-bordered table-hover">
    <thead class="bg-blue-300">
        <tr>
            @foreach ($columns as $colum)
                <th scope="col">{{ $colum }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                @foreach($row as $key => $value)
                    @if($key === "id")
                        <th scope="row">{{ $value }}</th>
                    @else
                        <td>{{ $value }}</td>
                    @endif
                @endforeach   
            </tr>
        @endforeach
    </tbody>
    @if($total > 0)
        <tfoot>
            <tr class="bg-blue-300">
                <th scope="row">Total</th>
                <td>{{ number_format($total, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <th class="bg-blue-300" scope="row">Diferencia</th>
                <td class={{ ($total - $input_value) < 0 ? "text-red-600" : "text-green-600" }}>{{ $total - $input_value }}</td>
            </tr>
        </tfoot>
    @endif
</table>
