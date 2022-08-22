@props([
    'disabled' => false,
    'options' => [],
    'defaultOptTitle' => '',
    'name' => '',
    'value' => ''
])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['name' => $name, 'class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
    @if($value === '')
        <option selected="selected" value hidden> {{ $defaultOptTitle }}</option>
    @endif
    @foreach ($options as $option)
        @if ($value === $option->key))
            <option value="{{ $option->key }}" selected>{{ $option->value }}</option>
        @else
            <option value="{{ $option->key }}">{{ $option->value }}</option>
        @endif
    @endforeach
</select>