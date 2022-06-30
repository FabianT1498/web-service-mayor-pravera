@props(['active'])

@php
    $classes = 'underline text-base font-medium text-gray-600 hover:text-gray-900 pt-4 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>