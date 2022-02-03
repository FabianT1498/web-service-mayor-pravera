@props(['modalID' => ''])

<!-- Main modal -->
<div id={{ $modalID }} aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-4 z-50 justify-center items-center h-modal md:h-full md:inset-0">
    <div class="relative px-4 w-full max-w-md h-full md:h-auto">
         <!-- Modal header -->
         <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600 bg-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                Entradas
            </h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="authentication-modal">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
            </button>
        </div>
        <!-- Modal content -->
        <div class="flex flex-col">
            <div class="overflow-x-auto shadow-md sm:rounded-lg">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden pt-8 pb-8 bg-gray-50">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Nro
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Dolares($)
                                    </th>
                                    <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                        Bolivares (Bs.s)
                                    </th>
                                    <th scope="col" class="p-4">
                                        Opcion
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">1</td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">
                                        <x-input 
                                            id="new_cash_register_worker" 
                                            placeholder="Nombre del cajero"
                                            class="w-full"
                                            type="text" 
                                            name="new_cash_register_worker" 
                                            :value="old('new_cash_register_worker') ? old('new_cash_register_worker') : ''" 
                                        />
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        12,000 Bs.F
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                                        <button  disabled class="bg-stone-300 flex justify-center w-8 h-8 items-center transition-colors duration-150 rounded-full shadow-lg">
                                            <i class="fas fa-times text-md text-red-600"></i>                        
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>