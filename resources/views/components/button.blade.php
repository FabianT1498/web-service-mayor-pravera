@props([
    'modalID' => '', 
    'variation' => 'default', 
    'disabled' => false,
    'dataTooltipTarget' => '',
    'dataTooltipPlacement' => ''
])
@php
    $classNames = 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent font-semibold text-xs text-white uppercase tracking-widest' . 
        ' hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ' .
            ($disabled ? 'bg-gray-400 cursor-default hover:bg-gray-400 active:bg-gray-400 ring-0 focus:ring-0 focus:border-transparent' : '');
    $variations = ['rounded' => 'rounded-md', 'default' => 'rounded-sm'];
@endphp
<button 
    {!! $dataTooltipTarget !== '' ? 'data-tooltip-target='. $dataTooltipTarget  : '' !!}
    {!! $dataTooltipPlacement !== '' ? 'data-tooltip-placement='. $dataTooltipPlacement  : '' !!}
    {!! $modalID ? 'data-modal-toggle='. $modalID : '' !!}
    {{ $attributes->merge(['type' => 'submit',  'class' => $classNames . ' ' . $variations[$variation] ]) }}
>
    {{ $slot }}
</button>
