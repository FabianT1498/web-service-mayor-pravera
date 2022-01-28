@props(['disabled' => false, 'options' => []])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
    <option hidden disabled value>Seleccione una caja</option>
    @foreach ($options as $key => $value)
        @if (old('cash_register_id') === $value->value)
            <option value="{{ $value->key }}" selected> {{ $value->value }}</option>
        @else
            <option value="{{ $value->key }}"> {{ $value->value }}</option>
        @endif

    @endforeach
</select>