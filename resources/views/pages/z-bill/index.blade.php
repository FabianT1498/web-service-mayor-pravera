
@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/z_bill_index.js') }}" defer></script>
@endsection

@section('main')
    <div class="w-10/12 mx-auto">
        <div class="mb-8">
            <h3 class="h3 mb-8 text-center">Reporte de números Z</h3>

            <form class="mb-4" id="form_filter" method="GET" action="{{ route('z_bill.generate-report') }}">
                <div 
                    id="date_range_picker" 
                    date-rangepicker 
                    class="grid gap-4 grid-cols-[150px_50px_150px_100px] grid-rows-4 mb-8 mx-auto items-center justify-center"
                >
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
                            value="{{$start_date}}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Seleccione fecha inicial"
                            autocomplete="off"
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
                            value="{{$end_date}}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Seleccione fecha final"
                            autocomplete="off"
                        >
                    </div>
                    <x-button>Generar reporte</x-button>
                </div>
          </form>
      </div>
    </div>
@endsection
