@props([
    'modalID' => '',
    'records' => [],
])

<!-- Main modal -->
<div id={{ $modalID }} aria-hidden="true" class="hidden fixed h-132 right-0 left-0 top-4 z-50 justify-center items-center md:h-full md:inset-0">
    <div class="flex flex-col relative w-full max-w-3xl h-full">
         <!-- Modal header -->
         <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600 bg-gray-200">
       
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
                    <div class="p-8 bg-gray-100 h-full flex justify-between">
                        <ul id={{ $modalID . '-list' }} class="basis-2/6 h-full overflow-x-hidden overflow-y-auto cursor-pointer scrollbar-thin scrollbar-track-slate-50 
                            scrollbar-thumb-slate-400 scrollbar-thumb-rounded">
                            <!-- <li 
                                aria-current="true" 
                                class="flex justify-between items-center h-1/4 w-full px-4 py-2 font-medium text-left text-white bg-blue-600 border-b border-gray-200 rounded-t-lg focus:outline-none"
                                data-id="1"
                            >
                                <div>
                                    <span>Titulo</span>
                                    <p>Descripcion</p>
                                </div>
                                <button
                                    type="button"
                                    data-modal="delete"
                                    class=" flex bg-white justify-center w-8 h-8 p-2 items-center transition-colors duration-150 rounded-full shadow-lg"
                                >
                                    <i class="fas text-red-600 fa-trash"></i>
                                </button>
                            </li>
                            <li class="flex justify-between items-center h-1/4 w-full px-4 py-2 hover:bg-gray-300 transition-colors ease-in-out duration-300">
                                <div>
                                    <span>Titulo</span>
                                    <p>Descripcion</p>
                                </div>
                                <button
                                    type="button"
                                    class=" flex bg-white justify-center w-8 h-8 p-2 items-center transition-colors duration-150 rounded-full shadow-lg"
                                >
                                    <i class="fas text-red-600 fa-trash"></i>
                                </button>
                            </li> -->
                        </ul>
                        <div class="basis-2/3 flex flex-col justify-between pl-4">
                            <div class="basis-1/12 flex flex-row">
                                <button data-modal="add" type="button" class="bg-blue-600 p-2 transition-colors ease-in-out duration-300 rounded-sm shadow-lg basis-3/12 font-medium text-white hover:bg-blue-500 ">
                                    Agregar nota
                                </button>
                            </div>
                            <div class="basis-10/12" id={{ $modalID . '-container' }}>
                                <div class="flex flex-col justify-between h-full" data-current-note="true">
                                    <input type="text" placeholder="Título" class="font-light text-xl text-gray-500 rounded-t-md min-w-0 border-solid border-0 border-b-2 border-blue-400 shadow-lg focus:outline-none focus:shadow-none
                                        focus:border-blue-600 focus:ring-0">
                                    <textarea class="w-full resize-none basis-4/5 border-none border-0 focus:border-none focus:outline-none focus:ring-0" placeholder="Descripción"
                                        name=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>