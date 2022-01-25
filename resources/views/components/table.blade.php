@props(['columns' => [], 'data' => []])
<table class="table">
    <thead>
        <tr>
            @foreach ($columns as $colum)
                <th scope="col">{{ $colum }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                @foreach($obj as $key => $value)
                    <td>{{ value }}</td>
                @endforeach   
            </tr>
        @endforeach
    </tbody>
    </table>
