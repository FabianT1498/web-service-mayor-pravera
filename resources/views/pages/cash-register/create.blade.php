@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/cash_register_create.js') }}" defer></script>
@endsection

@section('main')
    <ul class="w-3/6">
        @foreach ($errors->all() as $error)
            <li>
                {{ $error }}
            </li>
        @endforeach
    </ul>
    <form id="form" autocomplete="off" method="POST" action="{{ route('cash_register.store') }}">
        @csrf

        <div id="cash_register_data" class="w-10/12 mb-8 mx-auto">
            <h2 class="h2 text-center mb-8">Datos de la caja</h2>

            <!-- Cash register date -->
            <div class="flex items-center mb-4">
                <x-label class="w-1/12" for="date" :value="__('Fecha')" />
                <x-input class="ml-8 w-1/4" name="date" id="date" type="text" :value="$today_date" readonly/>
            </div>
        

            <!-- Cash register number-->
            <div class="flex items-center mb-4">
                <x-label class="w-1/12" for="cash_register" :value="__('Caja')" />
                <x-select-input
                    class="ml-8 w-1/4"
                    :options="$cash_registers_id_arr"
                    :defaultOptTitle="__('Seleccione la caja')"
                    id="cash_register_id"
                    :name="__('cash_register_user')"
                    :value="old('cash_register_user')"
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

            <div class="flex items-center mb-4">
                <x-label class="w-1/12" for="cash_register_worker" :value="__('Cajero/a:')" />
                <x-select-input
                    id="cash_register_worker"
                    class="ml-8 w-1/4"
                    :options="$cash_registers_workers_id_arr"
                    :defaultOptTitle="__('Seleccione el cajero/a')"
                    :name="__('worker_id')"
                    :value="old('worker_id')"
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
            <div id="new_cash_register_worker_container" class="hidden">
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
        </div>

        <div class="w-10/12 mb-8 mx-auto">
            <h2 class="h2">Ingresos en fisico</h2>
            <h3 class="h3">Dolares</h3>
            <!-- <x-label :value="__('Fecha de registro de la última tasa: ')" />
            <p data-dollar-exchange="dollar_exchange_date">{{ $dollar_exchange?->created_at ?? 'No ha sido registrada ninguna tasa' }}</p>

            <x-label class="w-56" :value="__('Cotización del $:')" />
            <p data-dollar-exchange="dollar_exchange_value" id="last-dollar-exchange-bs-label">{{ $dollar_exchange?->bs_exchange ?? 0 }} Bs.S</p> -->

            <div class="flex mb-8 items-center justify-between">
            
                <!-- Cash on liquid input (dollars) -->
                <x-label class="w-1/5" :value="__('Entradas de dolares en efectivo:')" />

                <x-input-with-button
                    :inputID="__('total_dollar_cash')"
                    :modalID="__('dollar_cash_record')"
                    :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))"
                    name="total_dollar_cash"
                    type="text"
                    class="w-1/4 ml-4"
                />
                                
                <x-label class="w-1/5 ml-8" for="liquid_money_dollars_total" :value="__('Cantidad de billetes por denominación ($):')" />
            
                <x-input-with-button
                    :inputID="__('total_dollar_denominations')"
                    :modalID="__('dollar_denominations_record')"
                    name="total_dollar_denominations"
                    type="text"
                    class="w-1/4 ml-4"
                />
            
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                            <td>&nbsp;</td>
                            <td>
                                <span id="total_dollar_cash_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_dollar_cash_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                        </tr>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                Total de las cantidades
                            </th>
                            <td>&nbsp;</td>
                            <td>
                            <span id="total_dollar_cash_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                            <td class="px-4 py-2">
                                <span id="total_dollar_cash_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- <div class="w-3/5 mx-auto bg-teal-600 h-px mb-8">&nbsp;</div>    -->
            
            <h3 class="h3">Bolívares</h3>

            <div class="flex mb-8 items-center">
                <x-label  :value="__('Cantidad de billetes por denominación (Bs):')" class="w-1/4"/>
                <x-input-with-button
                    :inputID="__('total_bs_denominations')"
                    :modalID="__('bs_denominations_record')"
                    :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    name="total_bs_denominations"
                    type="text"
                    class="w-1/4 ml-4"
                />
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                            <td>&nbsp;</td>
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
                    class="w-1/4 ml-4"
                    :inputID="__('total_point_sale_bs')"
                    :modalID="__('point_sale_bs')"
                    :currency="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    name="total_point_sale_bs"
                    type="text"
                />
            </div>
            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                            <td>&nbsp;</td>
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
                <x-label class="w-1/4" for="point_sale_dollar" :value="__('Entrada en punto de venta internacional ($):')" />
                <x-input
                    class="w-1/4 ml-4"
                    placeholder="{{'0.00 ' . config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR'))}}"
                    id="total_point_sale_dollar"
                    type="text"
                    name="total_point_sale_dollar"
                    :value="old('point_sale_dollar') ?? '0'"
                />
            </div>
            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                            <td>&nbsp;</td>
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
                    :inputID="__('total_pago_movil_bs')"
                    :modalID="__('pago_movil_record')"
                    :currency="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                    name="total_pago_movil_bs"
                    type="text"
                    class="w-1/4 ml-4"
                />
            </div>
            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                            <td>&nbsp;</td>
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
        
        <div class="w-10/12 mx-auto">
            <h2 class="h2 text-center mb-8">Entradas de Zelle ($)</h2>
    
            <div class="flex mb-8 items-center">
                <x-label class="w-1/5" :value="__('Total en Zelle:')" />
                <x-input-with-button
                    class="w-1/4 ml-4"
                    :inputID="__('total_zelle')"
                    :modalID="__('zelle_record')"
                    name="total_zelle"
                    type="text"
                />
            </div>

            <div class="mb-8">
                <table class="w-full text-sm text-center text-gray-600 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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
                            <td>&nbsp;</td>
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
                {{ __('Guardar') }}
            </x-button>

        </div>

        <x-modal-input-list
            :modalID="__('zelle_record')"
            :title="__('Entradas de zelle')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
        />

        <x-modal-input-list
            :modalID="__('dollar_cash_record')"
            :title="__('Entradas de dinero')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
        />

        <!-- <x-modal-input-list
            :modalID="__('bs_cash_record')"
            :title="__('Entradas de dinero')"
            :currency="config('constants.CURRENCIES.BOLIVAR')"
            :isBolivar="true"
        /> -->

        <x-modal-input-list
            :modalID="__('pago_movil_record')"
            :title="__('Entradas de pago móvil y transferencias')"
            :currency="config('constants.CURRENCIES.BOLIVAR')"
            :isBolivar="true"
        />

        <x-modal-input-denominations
            :modalID="__('dollar_denominations_record')"
            :denominations="['0.50', '1', '2', '5', '10', '20','50', '100']"
        />

        <x-modal-input-denominations
            :modalID="__('bs_denominations_record')"
            :denominations="['0.50', '1', '2', '5', '10', '20', '50','100', '200', '500']"
            :currency="__('Bs.S')"
        />

        <x-modal-point-sale-list
            :currency="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
            :modalID="__('point_sale_bs')"
        />
    </form>
  
@endsection
