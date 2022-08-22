@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bill_payable_schedules_create.js') }}" defer></script>
@endsection

@section('main')
    <form id="form" class="px-20" autocomplete="off" method="POST" action="{{ route('schedule.store') }}">
        @csrf
        <div id="cash_register_data" class="mb-8 mx-auto">
            <h2 class="h2 text-center mb-8">Datos de la programación</h2>

            <div id="dateRangePicker" date-rangepicker class="flex items-center w-3/4 mx-auto mb-8">
                <div class="flex items-center basis-1/2">
                    <span class="text-gray-500">Fecha inicial:</span>
                    <div class="ml-4 relative ">
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
                            name="start_date" 
                            type="text" 
                            value="{{ old('start_date') ? date('d-m-Y', strtotime(old('start_date'))) : $today_date }}"
                            id="startDate"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Seleccione la fecha inicial"
                            autocomplete="off"
                        >
                    </div>
                </div>

                <div class="flex items-center ml-4 basis-1/2">
                    <span class="text-gray-500">Fecha final:</span>
                    <div class="ml-4 relative">
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
                            name="end_date" 
                            type="text" 
                            value="{{ old('end_date') ? date('d-m-Y', strtotime(old('end_date'))) : $today_date }}"
                            id="endDate"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Seleccione la fecha final"
                            autocomplete="off"
                        >
                    </div>
                </div>
            </div>
            
            @if($errors->first('start_date') || $errors->first('end_date'))
                <p class="mb-4 text-sm text-red-600 dark:text-red-500">{{ $errors->first('start_date') }}</p>
            @endif

            <div class="w-3/4 flex mx-auto justify-end">
                <x-button :variation="__('rounded')">
                    {{ __('Guardar') }}
                </x-button>

            </div>
    </form>
  
@endsection
