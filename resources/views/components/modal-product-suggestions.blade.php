@props([
    'modalID' => '',
    'records' => [],
    'database' => ''
])

<!-- Main modal -->
<div id={{ $modalID }} aria-hidden="true" class="hidden fixed h-132 right-0 left-0 top-4 z-50 justify-center items-center md:h-full md:inset-0">
    <div class="flex flex-col relative w-full max-w-3xl h-full">
         <!-- Modal header -->
         <div class="flex justify-between items-start py-5 px-10 rounded-t border-b dark:border-gray-600 bg-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-3xl dark:text-white">
                {{ "Sugerencias" }}
            </h3>
            <button 
                type="button" 
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" 
                data-modal-toggle={{ $modalID }}
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" 
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" 
                    clip-rule="evenodd"></path></svg>  
            </button>
        </div>
        <!-- Modal content -->
        <div class="flex flex-col flex-grow ">
            <div class="shadow-md sm:rounded-lg h-full">
                <div class="inline-block min-w-full align-middle h-full">
                    <div class="p-8 bg-gray-100 h-full">
                        <div id={{ $modalID . '-loading' }} class="flex flex-col justify-center items-center">
                            <i class="loading animate-spin text-lg text-blue-600 fas fa-spinner"></i>
                            <p>Cargando...</p>
                        </div>
                        <div class="hidden w-11/12 mx-auto" id={{ $modalID . '-info' }}>
                            <p class="hidden mb-4" id={{ $modalID . '-message' }}></p>
                            <form class="flex items-center w-full mb-4" id={{ $modalID . '-form' }}>
                                <label for="percentSuggested">Porcentaje sugerido: </label>
                                <input type="hidden" name="database" value="{{ $database }}">
                                <input
                                    name="percentSuggested"
                                    id="percentSuggested"
                                    type="text"
                                    placeholder="0 %"
                                    min: "0"
                                    max: "100"
                                    value="0"
                                    class="ml-4 form-input w-20 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                >
                                <button 
                                    data-modal="add"
                                    data-tooltip-target="add-suggestion-tooltip"
                                    type="button" 
                                    class="bg-gray-800 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg ml-4"
                                >
                                    <i class="fas fa-plus  text-white"></i>                        
                                </button>
                                <div 
                                    id="add-suggestion-tooltip"
                                    role="tooltip" 
                                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                >
                                    Agregar sugerencia
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div> 
                            </form>
                            <table class="table table-bordered table-hover text-center overflow-y-scroll min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                                <thead class="sticky top-0 bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="col text-center align-middle">Fecha</th >
                                        <th class="col text-center align-middle">% Solicitado</th >
                                        <th class="col text-center align-middle">Usuario</th >
                                        <th class="col text-center align-middle">Estatus</th >
                                    </tr>
                                </thead>
                                <tbody class="w-full" id={{ $modalID . '-container' }}>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>