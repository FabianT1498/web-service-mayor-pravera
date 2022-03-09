@props([
    'columns' => [], 
    'data' => [],
    'hasOptions' => false
])
<div>
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
                    @if($hasOptions)
                        {{ $slot }}
                    @endif 
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-8">
        {{ $data->onEachSide(1)->links('pagination::tailwind') }}
    </div>
</div>
