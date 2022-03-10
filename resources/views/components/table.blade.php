@props([
    'columns' => [], 
    'data' => [],
    'actions'
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
                    @foreach($row->getAttributes() as $key => $value)
                        <td>{{ $value }}</td> 
                    @endforeach
                    @if(isset($actions))
                        <td>{{ $actions }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-8">
        {{ $data->onEachSide(1)->links('pagination::tailwind') }}
    </div>
</div>
