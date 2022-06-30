@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/cash_register_index.js') }}" defer></script>
@endsection

@section('main')
    <div>
        <div class="mb-4">
            <form class="mb-4 mx-auto w-11/12" id="form_filter" method="GET" action="{{ route('cash_register.index') }}">
                <p class="mb-4">Parametros de busqueda</p>
                <input id="page" name="page" type="hidden" value="1">
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
            </form>
            @if ($start_date && $end_date && $paginator->count() > 0)
                <div class="w-11/12 mx-auto">
                    <a 
                        class="underline text-sm text-gray-600 hover:text-gray-900 pt-4" 
                        href="{{ route('cash_register.interval_record_pdf', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                        data-generate-pdf="interval-report"
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
            <tbody id="cash-register-tbody">
                @foreach ($paginator as $key => $value)
                    <tr>
                        <td class="text-center"> 
                            <div class="relative">
                                {{ $key + 1 }}
                                @if ($value->notes_count > 0)
                                    <span
                                        data-tooltip-target="pending-notes"
                                        class="absolute top-0 right-0 h-2 w-2 transform bg-red-600 rounded-full motion-safe:animate-pulse">
                                            &nbsp;
                                    </span>
                                    <div 
                                        id="pending-notes"
                                        role="tooltip" 
                                        class="inline-block absolute invisible z-10 py-2 px-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                    >
                                        Notas existentes
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div> 
                                @endif
                            </div>
                        </td>
                        <td class="text-center">{{ $value->user_name }}</td>
                        <td class="text-center">{{ $value->cash_register_user }}</td>
                        <td class="text-center">{{ $value->worker_name }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->date)) }}</td>
                        <td class="text-center">{{ $value->status }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->updated_at)) }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center h-11 text-lg">
                                @if ($value->status === config('constants.CASH_REGISTER_STATUS.EDITING'))
                                    <form 
                                        method="POST" 
                                        action="{{ route('cash_register.finish', $value->id) }}"
                                        id="{{ 'close-cash-register-form-' . $value->id }}"
                                    >
                                        @csrf
                                        @method('PUT')
                                        <button
                                            type="button"
                                            data-modal-toggle="close-cash-register-modal"
                                            data-tooltip-target="close-tooltip"
                                            data_cash_register_id="{{ $value->id }}"
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
                                    data-generate-pdf="single-report"
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
        <div class="w-11/12 mx-auto">
            @if ($paginator->count() === 0)
                <p class="text-center">No hay registros</p>
            @endif
            <div class="mt-8 pb-32">
                {{ $paginator->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Modal para cerrar arqueo de caja -->
    <div id="close-cash-register-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="close-cash-register-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Estas seguro que quieres culminar este cierre de caja?</h3>
                    <button id="accept-cash-register-close" data-modal-toggle="close-cash-register-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Si, estoy seguro
                    </button>
                    <button data-modal-toggle="close-cash-register-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <x-modal-loading :title="__('Espere mientras se genera el documento PDF')"/>
@endsection