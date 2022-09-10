<form class=" border-slate-600" id="form_filter" method="GET" action="{{ route('bill_payable_groups.index') }}">
    <input id="page" name="page" type="hidden" value="1">
    <div class="flex items-center justify-between w-full">
        <div class="w-10/12">

            <div class="flex items-start mb-6">
            
                <div class="w-1/5 ml-4">
                    <span class="text-gray-500 mb-2 font-semibold inline-block">Proveedor</span>
                    <div class="relative">
                        <x-autocomplete 
                            :id="__('provider_search')"
                            :key="$cod_prov"
                            :value="$descrip_prov"
                            :name="__('cod_prov')"
                        />
                    </div>
                </div>
                
                <div class="w-1/5 ml-4">
                    <span class="text-gray-500 mb-2 font-semibold inline-block">Estatus: </span>   
                    <x-select-input 
                        :defaultOptTitle="__('Seleccione el estatus')"
                        id="status"
                        :value="$status"
                        :name="__('status')" 
                        :options="$statuses"
                        class="w-full relative"
                    />
                </div>
            </div>
        </div>
        
        <div class="flex items-center w-1/12">
            <div>
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
        </div>
    </div>
</form>