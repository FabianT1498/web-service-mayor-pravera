@props([
    'modalID' => '',
    'schedules' => [],
])
<!-- Main modal -->
<div id={{ $modalID }} aria-hidden="true" class="hidden fixed h-132 right-0 left-0 top-4 z-50 justify-center items-center md:h-full md:inset-0">
    <div class="flex flex-col relative w-full max-w-3xl h-full">
         <!-- Modal header -->
         <div class="flex justify-between items-start py-5 px-10 rounded-t border-b dark:border-gray-600 bg-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-3xl dark:text-white">
                {{ "Asignar factura a programación" }}
            </h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle={{ $modalID }}>
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
                        <div class="mb-8">
                            <div class="mb-4">
                                <h4 class="h4 text-center">Datos de la factura</h4>
                            </div>
                            <div class="flex flex-row items-center" id="billPayableContainer">
                                <p>Número de Documento: <span id="numeroDInfoModal"></span></p>
                                <p class="ml-4">Proveedor: <span id="proveedorInfoModal"></span></p>
                            </div>
                        </div>
                        <div id="scheduleContainer" class="flex flex-row items-center">
                            <label>Programación: </label>
                            <div class="w-1/5 ml-4">
                                <x-select-input
                                    class="w-full"
                                    :options="$schedules"
                                    :defaultOptTitle="__('Seleccione una programación')"
                                    id="scheduleSelect"
                                />
                            </div>
                            <p class="ml-8">Fecha Inicio: <span id="startDateSchedule"></span></p>
                            <p class="ml-4">Fecha Final: <span id="endDateSchedule"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>