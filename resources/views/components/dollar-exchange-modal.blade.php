<!-- Main modal -->
<div id="dollar-exchange-modal" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed h-108 right-0 left-0 top-4 z-50 justify-center items-center md:h-full md:inset-0">
    <div class="flex flex-col relative px-4 w-full max-w-md h-full">
         <!-- Modal header -->
         <div class="sticky top-0 flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600 bg-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                Tasa del dolar ($)
            </h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="dollar-exchange-modal" >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
            </button>
        </div>
        <!-- Modal content -->
        <div class="flex flex-col flex-grow">
            <div class="overflow-y-auto overflow-x-hidden shadow-md sm:rounded-lg h-full">
                <div class="inline-block min-w-full align-middle h-full">
                    <div class="overflow-hidden bg-gray-50 h-full">
                        <div class="p-8">
                            <p>Último valor de la tasa: <span id="dollar_exchange_value_bs" class="font-bold">{{ $dollar_exchange?->bs_exchange ?? 0 }} Bs.S</span></p>
                            <p>Fecha de registro: <span id="dollar_exchange_date_bs" class="font-bold">{{ $dollar_exchange?->created_at ?? 'Ninguna registrada' }}<span></p>
                            <div class="my-8 border-solid pb-2 border-b-2 mx-auto border-gray-300 w-2/3">
                                <p class="text-center ">Actualizar el valor de la tasa</p>
                            </div>
                            <div class="flex items-center justify-around mb-8">
                                <input 
                                    type="text"
                                    placeholder="0.00 Bs.S"
                                    id="dollar-exchange-bs-input"
                                    value={{ $dollar_exchange?->bs_exchange ?? 0 }}
                                    class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >
                                <x-button type="button" id="store_dollar_exchange_btn" class="basis-1/3 justify-center">
                                    {{ __('Guardar') }}
                                </x-button>      
                            </div>
                            <p id="dollar-exchange-message" class="hidden font-semibold text-center"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>