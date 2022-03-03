@props([
    'readonly' => true, 
    'modalID' => '', 
    'inputID' => '', 
    'currencySign' => "$",
    'value' => ''
])

<div class="h-10 flex w-56 bg-white items-center rounded-md shadow-md border-gray-300  focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50">
    <input
        {{ $readonly ? 'readonly' : '' }}
        placeholder="{{ '0.00 ' . $currencySign }}"
        value={{ $value !== '' ? $value : '0'}}
        data-currency={{ $currencySign }}
        {!! $attributes->merge(['class' => 'min-w-0 h-full rounded-tl-sm basis-5/6 rounded-bl-sm border-transparent focus:outline-none focus:shadow-none focus:border-transparent focus:ring-0']) !!}
        id={{ $inputID }}
    >
    <button data-modal-toggle={{ $modalID }} type="button" class="basis-1/6 h-10 bg-gray-200 rounded-tr-sm rounded-br-sm border-l-2 border-gray-100">
        <i class="fas fa-calculator text-base text-gray-900"></i>                   
    </button>
</div>
