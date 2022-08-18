<form class=" border-slate-600" id="form_filter" method="GET" action="{{ route('bill_payable.index') }}">
    <input id="page" name="page" type="hidden" value="1">
    <div class="flex items-center">

        <span class="text-gray-500">Fac. emitidas antes del:</span>
        <div class="ml-4 basis-1/6 relative">
            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                <svg 
                    class="w-5 h-5 text-gray-500 dark:text-gray-400" 
                    fill="currentColor" 
                    viewBox="0 0 20 20" 
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <input 
                id="endEmissionDatePicker"
                name="end_emission_date" 
                type="text" 
                value="{{$end_emission_date}}"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                placeholder="Seleccione fecha final"
                autocomplete="off"
            >
        </div>

        <span class="text-gray-500 ml-4">Número de factura</span>
        <div class="relative w-1/6 flex-initial ml-4">
            <input 
                data-form="filter"
                id="nroDoc"
                name="nro_doc" 
                type="text" 
                value="{{$nro_doc}}"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                autocomplete="off"
            >
        </div>

        <span class="text-gray-500 ml-4">Proveedor</span>
        <div class="relative w-2/6 flex-initial ml-4">
            <x-autocomplete 
                :id="__('provider_search')"
                :key="$cod_prov"
                :value="$descrip_prov"
                :name="__('cod_prov')"
            />
        </div>
      
        <span class="text-gray-500 ml-4">Es factura en $:</span>
        <input
            class=""
            data-form="filter"
            id="isDollar"
            type="checkbox"
            name={{__('is_dollar') }} 
            value={{ $is_dollar ? "1" : "0" }} 
            {{ $is_dollar ? "checked" : "" }}
        />

        <span class="text-gray-500 ml-4">Tipo de factura: </span>
        
        <div class="w-1/6 flex-initial ml-4">
            <x-select-input 
                :defaultOptTitle="__('Seleccione un tipo de factura')"
                id="billType"
                :value="$bill_type"
                :name="__('bill_type')" 
                :options="$bill_types"
                class="w-full relative"
            />
        </div>

        <div class="ml-8">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="ml-4">
            <button 
                type="button"
                id="cleanFormBtn"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center  dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                data-tooltip-target="clear-form-tooltip"
            >
                <i class="fas fa-trash-can-arrow-up"></i>
            </button>
        </div>

        <div 
            id="clear-form-tooltip"
            role="tooltip" 
            class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
        >
            Limpiar formulario
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div> 

        <!-- <div class="flex basis-1/5 items-center">
            
            <span class="text-gray-500 ml-8">Min. Dias disponibles</span>
            <div class="ml-4 w-1/12">
                <input
                    id="minAvailableDays"
                    type="text"
                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    name="min_available_days"
                    autocomplete="off"
                /> 
            </div>
            
            <span class="text-gray-500 ml-8">Max. Dias disponibles</span>
            <div class="ml-4 w-1/12">
                <input
                    id="maxAvailableDays"
                    type="text"
                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                    name="max_available_days"
                    autocomplete="off"
                /> 
            </div>
        </div> -->
    </div>
    
</form>