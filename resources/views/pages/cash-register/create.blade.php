@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/cash_register_create.js') }}" defer></script>
@endsection

@section('main')
    @foreach ($errors->all() as $error)
        <li>
            {{ $error }}
        </li>
    @endforeach
    <form id="form" autocomplete="off" method="POST" action="{{ route('cash_register.store') }}">
        @csrf
        <div class="w-10/12"><h3 class="h3 text-center mb-4">Datos de la caja</h3></div>

        <div id="cash_register_data" class="w-10/12 grid gap-4 grid-cols-[150px_200px_300px] grid-rows-4 mb-8 mx-auto items-center">

            <!-- Cash register date -->
            <x-label for="date" :value="__('Fecha')" />
            <x-input  name="date" id="date" type="text" :value="$today_date" readonly/>
            <div>&nbsp;</div>

            <!-- Cash register number-->
            <x-label for="cash_register" :value="__('Caja')" />
            <x-select-input
                :options="$cash_registers_id_arr"
                :defaultOptTitle="__('Seleccione la caja')"
                id="cash_register_id"
                :name="__('cash_register_user')"
                :value="old('cash_register_user')"
                required
            />
            <div id="cash_register_users_status" class="flex justify-between items-center">
                <i class="hidden loading animate-spin text-lg text-blue-600 fad fa-spinner-third"></i>
                <div
                    id="cash_register_users_message"
                    class="flex justify-around items-center {{ $cash_registers_workers_id_arr->count() === 0 ? '' : 'hidden'}}"
                >
                    <p class="basis-5/6">Ya se han creado arqueos para todos las cajas en esta fecha, por favor seleccione otra fecha</p>
                    <div class="rounded-full flex justify-center items-center w-8 h-8 p-2 motion-safe:animate-bounce bg-white shadow-md">
                        <i class="text-lg fas fa-exclamation text-blue-600"></i>
                    </div>
                </div>
            </div>

            <x-label for="cash_register_worker" :value="__('Cajero/a:')" />
            <x-select-input
                id="cash_register_worker"
                :options="$cash_registers_workers_id_arr"
                :defaultOptTitle="__('Seleccione el cajero/a')"
                :name="__('worker_id')"
                :value="old('worker_id')"
                required
            />
            <div>&nbsp;</div>

            <div class="flex items-center justify-between">
                   <x-label for="exist_cash_register_worker" :value="__('No esta registrado el cajero/a ?')" />
                   <input
                        id="cash_register_worker_exist_check"
                        type="checkbox"
                        name="exist_cash_register_worker"
                        value="{{ old('exist_cash_register_worker') ? old('exist_cash_register_worker') : 0 }}"
                    />
           </div>
           <div id="new_cash_register_worker_container" class="hidden">
                    <x-input
                        placeholder="Nombre del cajero"
                        class="w-full"
                        type="text"
                        name="new_cash_register_worker"
                        :value="old('new_cash_register_worker') ? old('new_cash_register_worker') : ''"
                    />
            </div>
            <div>&nbsp;</div>
        </div>

        <div class="w-10/12"><h3 class="h3 text-center mb-8">Ingresos en fisico</h3></div>

        <div class="w-10/12 grid gap-4 grid-cols-[150px_auto_150px_auto] mb-8 mx-auto items-center">
            <x-label :value="__('Fecha de registro de la última tasa: ')" />
            <p data-dollar-exchange="dollar_exchange_date">{{ $dollar_exchange?->created_at ?? 'No ha sido registrada ninguna tasa' }}</p>

            <x-label class="w-56" :value="__('Cotización del $:')" />
            <p data-dollar-exchange="dollar_exchange_value" id="last-dollar-exchange-bs-label">{{ $dollar_exchange?->bs_exchange ?? 0 }} Bs.S</p>

            <!-- Cash on liquid input (dollars) -->
            <x-label :value="__('Total de $ en efectivo:')" />
            <x-input-with-button
                :inputID="__('total_dollar_cash')"
                :modalID="__('dollar_cash_record')"
                :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))"
                name="total_dollar_cash"
                type="text"
            />

            <x-label for="liquid_money_dollars_total" :value="__('Total de billetes($) :')" />
            <x-input-with-button
                :inputID="__('total_dollar_denominations')"
                :modalID="__('dollar_denominations_record')"
                name="total_dollar_denominations"
                type="text"
            />

            <div class="col-span-2 mb-8">
                <p>Cantidad recuperada del sistema: <span id="total_dollar_cash_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}</p>
                <p>Diferencia: <span id="total_dollar_cash_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}</p>
            </div>
            <div class="col-span-2  mb-8">
                &nbsp;
            </div>


            <!-- Cash on liquid input (bolivares) -->
            <x-label :value="__('Total de Bs.S en efectivo:')" />
            <x-input-with-button
                :inputID="__('total_bs_cash')"
                :modalID="__('bs_cash_record')"
                :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                name="total_bs_cash"
                type="text"
            />
            <x-label  :value="__('Total de billetes(Bs.S) :')" />
            <x-input-with-button
                :inputID="__('total_bs_denominations')"
                :modalID="__('bs_denominations_record')"
                :currencySign="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                name="total_bs_denominations"
                type="text"
            />
            <div class="col-span-2 mb-8">
                <p>Cantidad recuperada del sistema: <span id="total_bs_cash_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}</p>
                <p>Diferencia: <span id="total_bs_cash_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}</p>
            </div>
            <div class="col-span-2  mb-8">
                &nbsp;
            </div>
        </div>

        <div class="w-10/12"><h3 class="h3 text-center mb-8">Ingresos en punto de venta</h3></div>

        <div class="w-10/12 grid gap-4 grid-cols-[150px_auto_150px_auto] mb-8 mx-auto items-center">

            <!-- Cash on punto de venta (bs) -->
            <x-label for="debit_card_payment_bs" :value="__('Total en punto de venta Bs:')" />
            <x-input-with-button
                :inputID="__('total_point_sale_bs')"
                :modalID="__('point_sale_bs')"
                :currency="__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))"
                name="total_point_sale_bs"
                type="text"
            />

            <x-label for="point_sale_dollar" :value="__('Total de $ en Punto de venta internacional:')" />
            <x-input
                placeholder="{{'0.00 ' . config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR'))}}"
                id="total_point_sale_dollar"
                class="block ml-4 w-56"
                type="text"
                name="total_point_sale_dollar"
                :value="old('point_sale_dollar') ?? '0'"
            />

            <div class="col-span-2">
                <p>Cantidad recuperada del sistema: <span id="total_point_sale_bs_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}</p>
                <p>Diferencia: <span id="total_point_sale_bs_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.BOLIVAR')))}}</p>
            </div>

            <div class="col-span-2">
                <p>Cantidad recuperada del sistema: <span id="total_point_sale_dollar_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}</p>
                <p>Diferencia: <span id="total_point_sale_dollar_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}</p>
            </div>
            <!-- Cash on punto de venta ($) -->
        </div>

        <div class="w-10/12"><h3 class="h3 text-center mb-8">Ingresos en Zelle</h3></div>

        <div class="w-10/12 grid gap-4 grid-cols-[150px_auto] mb-8 mx-auto items-center">
            <x-label :value="__('Total en Zelle:')" />
            <x-input-with-button
                :inputID="__('total_zelle')"
                :modalID="__('zelle_record')"
                name="total_zelle"
                type="text"
            />
            <div class="col-span-2">
                <p>Cantidad recuperada del sistema: <span id="total_zelle_saint">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}</p>
                <p>Diferencia: <span id="total_zelle_diff">0</span>&nbsp;{{__(config('constants.CURRENCY_SIGNS.' . config('constants.CURRENCIES.DOLLAR')))}}</p>
            </div>
        </div>

        <div class="w-10/12 flex mx-auto justify-end pt-8">
            <x-button :variation="__('rounded')">
                {{ __('Guardar') }}
            </x-button>

        </div>

        <x-modal-input-list
            :modalID="__('zelle_record')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
        />

        <x-modal-input-list
            :modalID="__('dollar_cash_record')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
        />

        <x-modal-input-list
            :modalID="__('bs_cash_record')"
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
            :modalID="__('point_sale_bs')"
        />
    </form>
@endsection
