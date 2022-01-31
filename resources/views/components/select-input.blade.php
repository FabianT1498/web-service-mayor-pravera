@props(['disabled' => false, 'options' => [], 'defaultOptTitle' => '', 'name' => ''])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['name' => $name, 'class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
    <option hidden disabled value {{ old($name) ? "" : 'selected' }}>{{ $defaultOptTitle }}</option>
    @foreach ($options as $key => $value)
        @if (old($name) && (old($name) === $value->value))
            <option value="{{ $value->key }}" selected> {{ $value->value }}</option>
        @else
            <option value="{{ $value->key }}"> {{ $value->value }}</option>
        @endif

    @endforeach
</select>