@props([
    'readonly' => true, 
    'modalID' => '', 
    'inputID' => '', 
    'currencySign' => "$",
    'value' => ''
])

<div {!! $attributes->merge(['class' => 'p-0 h-10 flex bg-white items-center justify-between rounded-md shadow-md border-gray-300 focus-within:border-indigo-300 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50']) !!} >
    <input
        {{ $readonly ? 'readonly' : '' }}
        placeholder="{{ ($value !== '' ? $value : '0.00') . ' ' . $currencySign }}"
        value={{ $value !== '' ? $value : '0'}}
        data-currency={{ $currencySign }}
        class="min-w-0 h-full rounded-tl-sm basis-4/6 rounded-bl-sm border-transparent focus:outline-none focus:shadow-none focus:border-transparent focus:ring-0"
        id={{ $inputID }}
    >
    <button data-modal-toggle={{ $modalID }} type="button" class="basis-1/6 h-10 bg-gray-200 rounded-tr-sm rounded-br-sm border-l-2 border-gray-100">
        <i class="fas fa-calculator text-base text-gray-900"></i>                   
    </button>
</div>
