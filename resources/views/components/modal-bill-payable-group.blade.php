@props([
    'modalID' => '',
])
<!-- Main modal -->
<div id={{ $modalID }} aria-hidden="true" class="hidden fixed h-132 right-0 left-0 top-4 z-50 justify-center items-center md:h-full md:inset-0">
    <div class="flex flex-col relative w-full max-w-3xl h-full">
         <!-- Modal header -->
         <div class="flex justify-between items-start py-5 px-10 rounded-t border-b dark:border-gray-600 bg-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 lg:text-3xl dark:text-white">
                {{ "Agrupar facturas" }}
            </h3>
            <button 
                type="button" 
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                id={{ $modalID . 'CloseBtn' }}
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
                    <div class="bg-gray-100 p-4">
                        <div class="mb-4 flex">
                            <h4 class="h4">Datos del grupo de facturas</h4>
                        </div>
                        <div id="{{ $modalID . 'InfoContainer'}}">
                            <p><span class="font-semibold">Proveedor:</span>&nbsp;<span id="{{ $modalID . 'InfoProvider'}}"></span></p>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-100 flex items-start justify-center">
                        <div id="{{ $modalID . 'InfoContainer' }}" class=" bg-white p-4 rounded-md shadow-md">
                            <div class="mb-2">
                                <div class="mb-4 flex">
                                    <h4 class="h4">Datos del grupo de factura</h4>
                                </div>
                                <x-select-input
                                    class="w-full"
                                    :defaultOptTitle="__('Seleccione un grupo')"
                                    id="{{ $modalID . 'Select' }}"
                                />
                            </div>
                            <p class="mb-2"><span class="font-semibold">Monto total:</span>&nbsp;<span id="{{ $modalID . 'TotalAmount' }}"></span></p>
                            <p class="mb-2"><span class="font-semibold">Monto pagado:</span>&nbsp;<span id="{{ $modalID . 'AmountPaid' }}"></span></p>
                        </div>
                       
                        <div class="bg-white w-5/12 p-4 rounded-md ml-8 flex flex-col items-center shadow-md">
                            <div class="mb-4 flex w-auto">
                                <h4 class="h4">Crear nuevo grupo</h4>
                            </div>
                            <x-button
                                id="{{ $modalID . 'AddGroupBtn' }}"
                                :variation="__('rounded')"
                                :type="__('button')"
                            >
                                {{ __('Agrupar facturas') }}
                            </x-button>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>