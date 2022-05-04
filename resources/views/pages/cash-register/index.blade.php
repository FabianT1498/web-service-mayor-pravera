@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/cash_register_index.js') }}" defer></script>
@endsection

@section('main')
    <div>
        <div class="mb-4">
            <form class="mb-4 mx-auto w-11/12" id="form_filter" method="GET" action="{{ route('cash_register.index') }}">
                <p class="mb-4">Parametros de busqueda</p>
                <div id="date_range_picker" date-rangepicker class="flex justify-between items-center mb-4 w-4/5">
                    <span class="text-gray-500">Desde</span>
                    <div class="relative basis-1/3">
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
                            value="{{$start_date}}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Seleccione fecha inicial"
                            autocomplete="off"
                        >
                    </div>
                    <span class="text-gray-500">hasta</span>
                    <div class="relative basis-1/3">
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
                            value="{{$end_date}}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Seleccione fecha final"
                            autocomplete="off"
                        >
                    </div>
                </div>
                <div class="flex items-center mb-4 w-4/5">
                    <span class="text-gray-500 mr-8">Estatus</span>
                    <x-select-input 
                        :options="$status_options" 
                        :defaultOptTitle="__('Seleccione un estatus')"
                        id="status"
                        :name="__('status')" 
                        :value="$status"
                        class="basis-1/3"
                    />
                </div>
                <div>
                    <x-button>Filtrar</x-button>
                </div>

            </form>
            @if ($start_date && $end_date && $records->count() > 0)
                <div class="w-11/12 mx-auto">
                    <a 
                        class="underline text-sm text-gray-600 hover:text-gray-900 pt-4" 
                        href="{{ route('cash_register.interval_record_pdf', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                    >
                        {{ __('Generar reporte por intervalo de tiempo en formato PDF') }}
                    </a>
                </div>
            @endif
        </div>
        <table class="table table-bordered table-hover mx-auto w-11/12 text-center">
            <thead class="bg-blue-300">
                <tr>
                    @foreach ($columns as $colum)
                        <th scope="col text-center align-middle">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $key => $value)
                    <tr>
                        <td class="text-center"> {{ $key + 1 }} </td>
                        <td class="text-center">{{ $value->user_name }}</td>
                        <td class="text-center">{{ $value->cash_register_user }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->date)) }}</td>
                        <td class="text-center">{{ $value->status }}</td>
                        <td class="text-center">{{ date('d-m-Y H:i', strtotime($value->updated_at)) }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center h-11 text-lg">
                                @if ($value->status === config('constants.CASH_REGISTER_STATUS.EDITING'))
                                    <form 
                                        method="POST" 
                                        action="{{ route('cash_register.finish', $value->id) }}" >
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
                                        href="{{ route('cash_register.edit', $value->id) }}" 
                                        class="ml-4 font-medium hover:text-teal-600 transition ease-in-out duration-500"
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
                                @endif
                                <a 
                                    href="{{ URL::to(route('cash_register.single_record_pdf', $value->id)) }}" 
                                    class="font-medium hover:text-teal-600 transition ease-in-out duration-500 {{ $value->status === config('constants.CASH_REGISTER_STATUS.COMPLETED') ? '' : 'ml-4' }}"
                                    data-tooltip-target="single-report-tooltip"
                                >
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <div 
                                    id="single-report-tooltip"
                                    role="tooltip" 
                                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                >
                                    Imprimir reporte
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($records->count() === 0)
            <p class="text-center">No hay registros</p>
        @endif
        <div id="" class="mt-8 pb-32">
            {{ $records->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    </div>
@endsection