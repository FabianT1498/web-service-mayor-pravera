@props(['disabled' => false, 'options' => []])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}>
    <option hidden disabled value>Seleccione una caja</option>
    @foreach ($options as $option)
        @if (old('cash_register_id') === $option)
            <option value="{{ $option }}" selected> {{ $option }}</option>
        @else
            <option value="{{ $option }}"> {{ $option }}</option>
        @endif

    @endforeach
</select>