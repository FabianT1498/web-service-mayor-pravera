<form class=" border-slate-600" id="form_filter" method="GET" action="{{ route('products.index') }}">
    <input id="page" name="page" type="hidden" value="1">
    <input id="prevConn" name="prev_conn" type="hidden" value="{{ $database }}">
    <div class="flex items-center mb-4">

  
        <span class="text-gray-500">Código o Descripción</span>
        <div class="relative w-1/6 flex-initial ml-4">
            <input 
                data-form="filter"
                id="descripcion"
                name="description" 
                type="text" 
                value="{{$descrip}}"
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                autocomplete="off"
            >
        </div>
      
        <span class="text-gray-500 ml-8">Instancia</span>
        <div class="w-1/6 flex-initial ml-4">
            <x-select-input 
                data-form="filter"
                :defaultOptTitle="__('Seleccione una instancia')"
                id="productInstance"
                :value="$instance"
                :name="__('product_instance')" 
                :options="$instances"
                class="w-full relative"
            />
        </div>

        <span class="text-gray-500 ml-8">Hay existencia</span>
        <div class="flex-initial ml-4">
            <input
                data-form="filter"
                id="thereExistance"
                type="checkbox"
                name={{__('there_existance')}} 
                value={{ $there_existance ? "1" : "0" }} 
                {{ $there_existance ? "checked" : "" }}
            />    
        </div>
        
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ml-8">
            <i class="fas fa-search"></i>
        </button>
    </div>
    <div class="flex items-center">
        <span class="text-gray-500">Base de datos: </span>
        <div class="w-1/6 flex-initial ml-4">
            <x-select-input 
                :defaultOptTitle="__('Seleccione una base de datos')"
                id="databaseName"
                :value="$database"
                :name="__('database')" 
                :options="$databases"
                class="w-full relative"
            />
        </div>
    </div>
</form>