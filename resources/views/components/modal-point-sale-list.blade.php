@props([
    'modalID' => '',
    'currency' => '$',
    'records' => [],
    'banks' => []
])

<!-- Main modal -->
<div id={{ $modalID }} data-currency={{ $currency }} aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed h-108 right-0 left-0 top-4 z-50 justify-center items-center md:h-full md:inset-0">
    <div class="flex flex-col relative w-full max-w-xl h-full">
         <!-- Modal header -->
         <div class="sticky top-0 flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600 bg-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                Entrada en los puntos de venta
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
                                    <th scope="col" class="w-1/12 py-3 pl-6 text-xs text-center tracking-wider  text-gray-700 uppercase dark:text-gray-400">
                                        Nro
                                    </th>
                                    <th scope="col" class="w-1/5 py-3 pl-3 text-xs tracking-wider text-center text-gray-700 uppercase dark:text-gray-400">
                                        Banco
                                    </th>
                                    <th scope="col" class="w-1/5 py-3 pl-3 text-xs tracking-wider text-center text-gray-700 uppercase dark:text-gray-400">
                                        {{ 'Debito (' . $currency . ')' }}
                                    </th>
                                    <th scope="col" class="w-1/5 py-3 pl-3 text-xs tracking-wider text-center text-gray-700 uppercase dark:text-gray-400">
                                        {{ 'Credito (' . $currency . ')' }}
                                    </th>
                                    <th scope="col" class="w-1/12 pl-3 pr-6 text-center">
                                        <button data-modal="add" type="button" class="bg-gray-800 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg">
                                            <i class="fas fa-plus  text-white"></i>                        
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @if (count($records) > 0 && count($records['bank']) > 0 && (count($records['bank']) === count($records['credit']))
                                        && (count($records['bank']) === count($records['debit'])))
                                    @foreach($records['bank'] as $key => $bank)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id={{$key}}>
                                            <td data-table="num-col" class="py-4 pl-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">{{ $key + 1}}</td>
                                            
                                            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                                                <select class="w-full form-select" name={{$modalID . "_bank[]"}}>
                                                    <option value="{{ $bank }}" selected>{{ $bank }}</option>
                                                    @foreach($banks as $bank)
                                                        <option value="{{ $bank->name }}">{{ $bank->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                                                <input 
                                                    type="text" 
                                                    data-point-sale-type="debit" 
                                                    value="{{ $records['debit'][$key]->amount }}"
                                                    placeholder="{{ '0.00 ' . $currency }}"
                                                    id="{{$modalID . '_debit_' . $key}}"
                                                    name="{{ $modalID . '_debit[' . $records['debit'][$key]->id . ']' }}" 
                                                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                >
                                            </td>

                                            <td data-table="convertion-col" class="pl-3 py-4 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                <input 
                                                    type="text" 
                                                    data-point-sale-type="credit" 
                                                    value="{{ $records['credit'][$key]->amount }}"
                                                    placeholder="{{ '0.00 ' . $currency }}"
                                                    id="{{$modalID . '_credit_' . $key}}"
                                                    name="{{  $modalID . '_credit[' . $records['credit'][$key]->id . ']' }}" 
                                                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                >
                                            </td>
                                            
                                            <td class="py-4 pl-3 text-sm text-center font-medium whitespace-nowrap">
                                                <button data-modal="delete" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                                                    <i class="fas fa-times  text-white"></i>                        
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>