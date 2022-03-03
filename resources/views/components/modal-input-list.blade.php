@props(['modalID' => '', 'currency' => '', 'isBolivar' => false, 'records' => []])

@php
    $bolivar = config("constants.CURRENCIES.BOLIVAR");
    $currency_sign = config("constants.CURRENCY_SIGNS." . $currency);
@endphp

<!-- Main modal -->
<div id={{ $modalID }} data-currency={{ $currency }} aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed h-108 right-0 left-0 top-4 z-50 justify-center items-center md:h-full md:inset-0">
    <div class="flex flex-col relative px-4 w-full h-full {{ !$isBolivar ? "max-w-xl" : "max-w-md" }}">
         <!-- Modal header -->
         <div class="sticky top-0 flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600 bg-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                Entradas
            </h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle={{ $modalID }}>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
            </button>
        </div>
        <!-- Modal content -->
        <div class="flex flex-col flex-grow">
            <div class="overflow-y-auto overflow-x-hidden shadow-md sm:rounded-lg h-full">
                <div class="inline-block min-w-full align-middle h-full">
                    <div class="overflow-hidden pt-8 pb-8 bg-gray-50 h-full">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 pl-6 pr-3 text-xs text-center tracking-wider  text-gray-700 uppercase dark:text-gray-400">
                                        Nro
                                    </th>
                                    <th scope="col" class="py-3 pl-3 text-xs tracking-wider text-center text-gray-700 uppercase dark:text-gray-400">
                                        {{ "Entradas (" . $currency_sign . ")" }}
                                    </th>
                                    @if(!$isBolivar)
                                        <th scope="col" class="py-3 px-6 text-xs tracking-wider text-center text-gray-700 uppercase dark:text-gray-400">
                                            {{ "Conversion (" . config("constants.CURRENCY_SIGNS." . $bolivar) . ")" }}
                                        </th>
                                    @endif
                                    <th scope="col" class="w-1/12 pl-3 pr-6 text-center">
                                        <button data-modal="add" type="button" class="bg-gray-800 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg">
                                            <i class="fas fa-plus  text-white"></i>                        
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($records as $key => $record)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id="{{$key}}">
                                        <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">{{ $key + 1}}</td>
                                        <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                                            <input 
                                                type="text"
                                                placeholder="0.00 {{$currency_sign}}"
                                                id="{{ $modalID . "_" . $key }}" 
                                                name="{{$modalID . "[" . $record->id . "]" }}" 
                                                class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                value="{{ $record->amount }}"
                                            >
                                        </td>
                                        @if(!$isBolivar)
                                            <td data-table="convertion-col" class="py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ (($dollar_exchange?->bs_exchange ?? 0) * $record->amount) . " " . config("constants.CURRENCY_SIGNS." . $bolivar) }}
                                            </td>
                                        @endif
                                        <td class="py-4 pl-3 pr-6 text-sm text-center font-medium whitespace-nowrap">
                                            <button data-modal="remove" data-del-row="$key" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                                                <i class="fas fa-times  text-white"></i>                        
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>