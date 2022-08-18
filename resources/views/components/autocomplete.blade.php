@props([
    'disabled' => false, 
    'id' => '',
    'placeholder' => "Ingrese un texto"
])

<div 
    id="{{ $id }}" 
    {!! $attributes->merge(['class' => 'flex flex-col items-center justify-start rounded-md w-full shadow-sm border-gray-300 relative']) !!}
>
    <input id="{{ $id . '_hidden' }}"type="hidden" value="" name="cod_prov">
    <input 
        type="text" 
        placeholder="{{ $placeholder }}" 
        id="{{ $id . '_input' }}" 
        {{ $disabled ? 'disabled' : '' }} 
        class="w-full rounded-tl-md rounded-tr-md focus:ring-0 border-gray-300 focus:border-gray-400"
    >
    <div class="relative w-full">
        <ul class="w-full list-none bg-slate-300 rounded-b-md absolute hidden" id="{{ $id . '_results' }}">
        </ul>
    </div>
</div>
