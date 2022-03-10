@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/cash_register_index.js') }}" defer></script>
@endsection

@section('main')
    <div class="w-10/12 mx-auto">
        <div class="mb-6">
            <form method="GET" action="route('cash_register.index')">
                <div class="flex justify-evenly items-center">
                    <div id="date_range_picker" date-rangepicker class="flex items-center">
                        <div class="relative">
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
                                id="start_date"
                                name="start_date" 
                                type="text" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                placeholder="Seleccione fecha inicial"
                            >
                        </div>
                        <span class="mx-4 text-gray-500">hasta</span>
                        <div class="relative">
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
                                id="end_date"
                                name="end_date" 
                                type="text" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                placeholder="Seleccione fecha final"
                            >
                        </div>
                    </div>
                    <div>
                        <span class="mx-4 text-gray-500">Estatus</span>
                        <x-select-input 
                            :options="$status_options" 
                            :defaultOptTitle="__('Seleccione un estatus')"
                            id="status"
                            :name="__('status')" 
                            :value="$status" 
                        />
                    </div>
                    <x-button>Filtrar</x-button>
                </div>
            </form>
        </div>
        <table class="table table-bordered table-hover">
            <thead class="bg-blue-300">
                <tr>
                    @foreach ($columns as $colum)
                        <th scope="col">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        @foreach($record->getAttributes() as $key => $value)
                            <td>{{ $value }}</td> 
                        @endforeach
                        <td>
                            <div class="flex items-center justify-between h-11 text-lg">
                                <form 
                                    method="POST" 
                                    action="{{ route('cash_register.finish', $record->id) }}" 
                                    class="inline-block w-7"
                                >
                                    @csrf
                                    @method('PUT')
                                    <button
                                        data-tooltip-target="close-tooltip"
                                        class="font-medium hover:text-teal-600 transition ease-in-out duration-500"
                                        
                                    >
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <div 
                                        id="close-tooltip"
                                        role="tooltip" 
                                        class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                    >
                                        Cerrar arqueo de caja
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div> 
                                </form>
                                <a 
                                    href="{{ route('cash_register.edit', $record->id) }}" 
                                    class="font-medium hover:text-teal-600 transition ease-in-out duration-500"
                                    data-tooltip-target="edit-tooltip"
                                >
                                    <i class="fas fa-pencil"></i>
                                </a>
                                <div 
                                    id="edit-tooltip"
                                    role="tooltip" 
                                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                >
                                    Editar
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div> 
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-8">
            {{ $records->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    </div>
@endsection