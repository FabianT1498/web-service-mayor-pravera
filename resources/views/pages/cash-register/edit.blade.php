@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/cash_register_edit.js') }}" defer></script>
@endsection

@section('main')
    
    <form id="form" autocomplete="off" method="POST" action="{{ route('cash_register.update', $cash_register_data->id) }}">
        @csrf
        @method('PUT')

        <input id="id" type="hidden" value="{{$cash_register_data->id}}">
        
        <div id="cash_register_data" class="w-10/12 mb-8 mx-auto">
            <h2 class="h2 text-center mb-8">Datos de la caja</h2>
            <div>
                <!-- Cash register date -->
                <div class="flex items-center {{ $errors->first('date') ? 'mb-2' : 'mb-4' }}">
                    <x-label class="w-1/12" for="date" :value="__('Fecha')" />
                    <x-input class="ml-8 w-1/4" name="date" id="date" type="text" :value="$date" readonly/>
                </div>
                @if($errors->first('date'))
                    <p class="mb-4 text-sm text-red-600 dark:text-red-500">{{ $errors->first('date') }}</p>
                @endif
                        
                <!-- Cash register number-->
                <div class="flex items-center {{ $errors->first('cash_register_user') ? 'mb-2' : 'mb-4' }}">
                    <x-label class="w-1/12" for="cash_register" :value="__('Caja')" />
                    <x-select-input
                        class="ml-8 w-1/4"
                        :options="$cash_registers_id_arr"
                        :defaultOptTitle="__('Seleccione la caja')"
                        id="cash_register_id"
                        :name="__('cash_register_user')"
                        :value="old('cash_register_user') ? old('cash_register_user') : $cash_register_data->cash_register_user"
                        required
                    />

                    <div id="cash_register_users_status" class="ml-4 w-1/2 flex justify-around items-center">
                        <i class="hidden loading animate-spin text-lg text-blue-600 fad fa-spinner-third"></i>
                        <div
                            id="cash_register_users_message"
                            class="flex items-center justify-around {{ $cash_registers_id_arr->count() > 0 ? 'hidden' : ''}}"
                        >
                            <p class="w-4/5">Ya se han creado arqueos para todos las cajas en esta fecha, por favor seleccione otra fecha</p>
                            <div class="rounded-full flex justify-center items-center w-8 h-8 motion-safe:animate-bounce bg-white shadow-md">
                                <i class="text-lg fas fa-exclamation text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                </div>
                @if($errors->first('cash_register_user'))
                    <p class="mb-4 text-sm text-red-600 dark:text-red-500">{{ $errors->first('cash_register_user') }}</p>
                @endif

                <div class="flex items-center {{ $errors->first('worker_id') ? 'mb-2' : 'mb-4' }}">
                    <x-label class="w-1/12" for="cash_register_worker" :value="__('Cajero/a:')" />
                    <x-select-input
                        id="cash_register_worker"
                        class="ml-8 w-1/4"
                        :options="$cash_registers_workers_id_arr"
                        :defaultOptTitle="__('Seleccione el cajero/a')"
                        :name="__('worker_id')"
                        :value="old('worker_id') ? old('worker_id') : $cash_register_data->worker_id"
                        required
                    />
                    @if ($cash_registers_workers_id_arr->count() === 0)
                        <div id="cash_register_worker_status" class="ml-4 w-1/2 flex justify-around items-center">
                            <p class="w-4/5">No hay ningún cajero/a registrado, por favor seleccione el checkbox 'no está registrado'</p>
                            <div class="rounded-full flex justify-center items-center w-8 h-8 motion-safe:animate-bounce bg-white shadow-md">
                                <i class="text-lg fas fa-exclamation text-blue-600"></i>
                            </div>
                        </div>
                    @endif
                </div>
                @if($errors->first('worker_id'))
                    <p class="mb-4 text-sm text-red-600 dark:text-red-500">{{ $errors->first('worker_id') }}</p>
                @endif

                <div class="flex items-center mb-4">
                    <div>
                        <x-label class="inline" for="exist_cash_register_worker" :value="__('No esta registrado el cajero/a ?')" />
                        <input
                            class="ml-1"
                            id="cash_register_worker_exist_check"
                            type="checkbox"
                            name="exist_cash_register_worker"
                            value="{{ old('exist_cash_register_worker') ? old('exist_cash_register_worker') : 0 }}"
                        />    
                    </div>
                </div>
                <div id="new_cash_register_worker_container" class="hidden {{ $errors->first('new_cash_register_worker') ? 'mb-2' : 'mb-4' }}">
                    <div class="flex items-center">
                        <x-label  for="cash_register_worker" :value="__('Nombre del nuevo cajero/a:')" />
                        <x-input
                            placeholder="Nombre del cajero"
                            class="ml-8 w-1/4"
                            type="text"
                            name="new_cash_register_worker"
                            :value="old('new_cash_register_worker') ? old('new_cash_register_worker') : ''"
                        />
                    </div>
                </div>
                @if($errors->first('new_cash_register_worker'))
                    <p class="mb-4 text-sm text-red-600 dark:text-red-500">{{ $errors->first('new_cash_register_worker') }}</p>
                @endif
                
            </div>
        </div>

        <div class="w-10/12 mb-8 mx-auto">
            <h2 class="h2 text-center mb-8">Valor de la tasa en esta fecha</h2>
            <p><span class="font-semibold">Cotización del dolar:</span>&nbsp;<span>{{ $old_dollar_exchange?->bs_exchange ?? 0 }} Bs.S</span></p>
        </div>

        <div class="w-10/12 mb-8 mx-auto">
            <h2 class="h2">Ingresos en fisico</h2>
            <h3 class="h3">Dolares</h3>

            <div class="flex mb-8 items-center justify-between">
            
                <!-- Cash on liquid input (dollars) -->
                <x-label class="w-1/5" :value="__('Entradas de dolares en efectivo:')" />

                <x-input-with-button
                    :inputID="__('total_dollar_cash_input')"
                    :modalID="__('dollar_cash_record')"
                    :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))"
                    name="total_dollar_cash"
                    type="text"
                    :value="old('total_dollar_cash') ? old('total_dollar_cash') : $total_dollar_cash"
                    class="w-1/4 ml-4"
                />
                                
                <x-label class="w-1/5 ml-8" for="liquid_money_dollars_total" :value="__('Cantidad de billetes por denominación ($):')" />
            
                <x-input-with-button
                    :inputID="__('total_dollar_denominations_input')"
                    :modalID="__('dollar_denominations_record')"
                    name="total_dollar_denominations"
                    type="text"
                    :value="old('total_dollar_denominations') ? old('total_dollar_denominations') : $total_dollar_denominations"
                    class="w-1/4 ml-4"
                />
            
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de los detalles
                            </th>
                            <td>
                                <span id="total_dollar_cash">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td>
                                <span class="total_dollar_cash_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_dollar_cash_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de las cantidades
                            </th>
                            <td>
                                <span id="total_dollar_denominations">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td>
                                <span class="total_dollar_cash_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_dollar_cash_denomination_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="h3">Bolívares</h3>

            <div class="flex mb-8 items-center">
                <!-- Cash on liquid input (bolivares) -->
                <x-label  :value="__('Cantidad de billetes por denominación (Bs):')" class="w-1/4"/>
                <x-input-with-button
                    :inputID="__('total_bs_denominations_input')"
                    :modalID="__('bs_denominations_record')"
                    :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    name="total_bs_denominations"
                    type="text"
                    :value="old('total_dollar_denominations') ? old('total_bs_denominations') : $total_bs_denominations"
                    class="w-1/4 ml-4"
                />
            </div>
            
            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de las cantidades
                            </th>
                            <td>
                                <span id="total_bs_denominations">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td>
                                <span id="total_bs_cash_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_bs_cash_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
        <div class="w-10/12 mx-auto">
            <h2 class="h2">Ingresos en punto de venta</h2>
            <h3 class="h3">Bolívares</h3> 
            
            <div class="flex mb-8 items-center">

                <!-- Cash on punto de venta (bs) -->
                <x-label class="w-1/5" for="debit_card_payment_bs" :value="__('Entradas en punto de venta (Bs):')" />
                <x-input-with-button
                    class="w-1/4"
                    :inputID="__('total_point_sale_bs_input')"
                    :modalID="__('point_sale_bs')"
                    :currency="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    name="total_point_sale_bs"
                    :value="old('total_point_sale_bs') ? old('total_point_sale_bs') : $total_point_sale_bs"
                    type="text"
                />
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de los detalles
                            </th>
                            <td>
                                <span id="total_point_sale_bs">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td>
                                <span id="total_point_sale_bs_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_point_sale_bs_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="h3">Dolares</h3>

            <div class="flex mb-8 items-center">
                <x-label class="w-1/5" for="point_sale_dollar" :value="__('Entrada en punto de venta internacional ($):')" />
                <x-input
                    class="w-1/4 ml-4"
                    placeholder="{{'0.00 ' . config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR'))}}"
                    id="total_point_sale_dollar_input"
                    type="text"
                    name="total_point_sale_dollar"
                    :value="old('point_sale_dollar') ? old('point_sale_dollar') : ($point_sale_dollar_record ? $point_sale_dollar_record->amount : 0)"
                />
        
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de los detalles
                            </th>
                            <td>
                                <span id="total_point_sale_dollar">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td>
                                <span id="total_point_sale_dollar_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_point_sale_dollar_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Cash on punto de venta ($) -->

        </div>

        <div class="w-10/12 mx-auto">
            <h2 class="h2 text-center mb-8">Transferencias y pago móviles</h2>
            <div class="flex mb-8 items-center">
                
                <x-label class="w-1/5" for="total_pago_movil_bs" :value="__('Entradas de transferencias:')" />
                <x-input-with-button
                    :inputID="__('total_pago_movil_bs_input')"
                    :modalID="__('pago_movil_record')"
                    :currency="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    name="total_pago_movil_bs"
                    type="text"
                    :value="old('total_pago_movil_bs') ? old('total_pago_movil_bs') : $total_pago_movil_bs"
                    class="w-1/4 ml-4"
                />             
            </div>
            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de los detalles
                            </th>
                            <td>
                                <span id="total_pago_movil_bs">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td>
                                <span id="total_pago_movil_bs_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_pago_movil_bs_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>   
            
        </div>

        <div class="w-10/12 mb-8 mx-auto">
            <h2 class="h2">Ingresos en AMEX</h2>
            <h3 class="h3">Bolívares</h3>

            <div class="flex mb-8 items-center justify-between">
            
                <!-- Cash on liquid input (dollars) -->
                <x-label class="w-1/5" :value="__('Entradas de AMEX:')" />

                <x-input-with-button
                    :inputID="__('total_amex_input')"
                    :modalID="__('amex_record')"
                    :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    :name="__('total_amex')"
                    type="text"
                    class="w-1/4 ml-4"
                    :value="old('total_amex') ? old('total_amex') : $total_amex_bs"
                />
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de los detalles
                            </th>
                            <td>
                                <span id="total_amex">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td>
                                <span class="total_amex_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_amex_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-10/12 mb-8 mx-auto">
            <h2 class="h2">Ingresos en Todoticket</h2>
            <h3 class="h3">Bolívares</h3>

            <div class="flex mb-8 items-center justify-between">
            
                <!-- Cash on liquid input (dollars) -->
                <x-label class="w-1/5" :value="__('Entradas de Todoticket:')" />

                <x-input-with-button
                    :inputID="__('total_todoticket_input')"
                    :modalID="__('todoticket_record')"
                    :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    :name="__('total_todoticket')"
                    type="text"
                    class="w-1/4 ml-4"
                    :value="old('total_todoticket') ? old('total_todoticket') : $total_todoticket_bs"
                />
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de los detalles
                            </th>
                            <td>
                                <span id="total_todoticket">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td>
                                <span class="total_todoticket_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_todoticket_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-10/12 mx-auto">
            <h2 class="h2 text-center mb-8">Entradas de Zelle ($)</h2>    
            <div class="flex mb-8 items-center">
                <x-label class="w-1/5" :value="__('Total en Zelle:')" />
                <x-input-with-button
                    class="w-1/4 ml-4"
                    :inputID="__('total_zelle_input')"
                    :modalID="__('zelle_record')"
                    name="total_zelle"
                    :value="old('total_zelle') ? old('total_zelle') : $total_zelle"
                    type="text"
                />
            </div>
            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-2">
                                Referencia
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total ingresado
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Total SAINT
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de los detalles
                            </th>
                            <td>
                                <span id="total_zelle">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td>
                                <span id="total_zelle_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_zelle_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> 
        </div>

        <div class="w-10/12 flex mx-auto justify-end mb-32">
            <x-button :variation="__('rounded')">
                {{ __('Guardar cambios') }}
            </x-button>

        </div>

        <x-modal-input-list
            :modalID="__('dollar_cash_record')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
            :title="__('Entradas de dinero')"
            :records="$dollar_cash_records"
        />

        <x-modal-input-list
            :modalID="__('todoticket_record')"
            :title="__('Entradas de todoticket')"
            :currency="config('constants.CURRENCIES.BOLIVAR')"
            :records="$todoticket_records"
        />

        <x-modal-input-list
            :modalID="__('amex_record')"
            :title="__('Entradas de AMEX')"
            :currency="config('constants.CURRENCIES.BOLIVAR')"
            :records="$amex_records"
        />

        <x-modal-input-list
            :modalID="__('pago_movil_record')"
            :currency="config('constants.CURRENCIES.BOLIVAR')"
            :title="__('Entradas de pago móvil y transferencias')"
            :isBolivar="true"
            :records="$pago_movil_bs_records"
        />

        <x-modal-input-denominations
            :modalID="__('dollar_denominations_record')"
            :denominations="['0.50', '1', '2', '5', '10', '20','50', '100']"
            :records="$dollar_denomination_records"
        />

        <x-modal-input-denominations
            :modalID="__('bs_denominations_record')"
            :denominations="['0.50', '1', '2', '5', '10', '20', '50','100', '200', '500']"
            :currency="__('Bs.S')"
            :records="$bs_denomination_records"
        />

        <x-modal-point-sale-list
            :modalID="__('point_sale_bs')"
            :records="$point_sale_bs_records_arr"
            :banks="$banks"
        />

        <x-modal-input-list
            :modalID="__('zelle_record')"
            :title="__('Entradas de zelle')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
            :records="$zelle_records"
        />
    </form>
  
@endsection
