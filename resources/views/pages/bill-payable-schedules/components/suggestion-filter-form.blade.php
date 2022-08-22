<form class="mb-4 mx-auto w-11/12" id="form_filter" method="GET" action="{{ route('products.productsSuggestions') }}">
    <p class="mb-4">Parametros de busqueda</p>
    <input id="page" name="page" type="hidden" value="1">
    <div class="flex items-center mb-4">

        <span class="text-gray-500 ml-8">Estatus de sugerencia</span>
        <div class="w-1/6 flex-initial ml-4">
            <x-select-input 
                :defaultOptTitle="__('Seleccione un estatus')"
                id="suggestionStatus"
                :value="$status"
                :name="__('status')" 
                :options="$suggestion_status"
                class="w-full relative"
            />
        </div>

        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ml-auto">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>