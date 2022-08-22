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
                    <div class="p-8 bg-gray-100 flex justify-between">
                        <div class="mb-8 bg-white w-5/12 p-4 rounded-md">
                            <div class="mb-4 flex">
                                <h4 class="h4">Datos de la factura</h4>
                            </div>
                            <div id="billPayableContainer">
                                <p class="mb-2"><span class="font-semibold">Número de Documento:</span>&nbsp;<span id="numeroDInfoModal"></span></p>
                                <p><span class="font-semibold">Proveedor:</span>&nbsp;<span id="proveedorInfoModal"></span></p>
                            </div>
                        </div>
                        <div id="scheduleContainer" class="mb-8 bg-white w-5/12 p-4 rounded-md">
                            <div class="mb-2">
                                <div class="mb-4 flex">
                                    <h4 class="h4">Datos de la programación</h4>
                                </div>
                                <x-select-input
                                    class="w-full"
                                    :options="$schedules"
                                    :defaultOptTitle="__('Seleccione una programación')"
                                    id="scheduleSelect"
                                />
                            </div>
                            <p class="mb-2"><span class="font-semibold">Fecha Inicio:</span>&nbsp;<span id="startDateSchedule"></span></p>
                            <p><span class="font-semibold">Fecha Final:</span>&nbsp;<span id="endDateSchedule"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>