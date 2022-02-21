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
    <form id="form" autocomplete="off" method="POST" action="{{ route('cash_register_step_one.post') }}">
        @csrf
        <div class="w-10/12"><h3 class="h3 text-center mb-4">Datos de la caja</h3></div>

        <div id="cash_register_data" class="w-10/12 grid gap-4 grid-cols-[200px_250px] grid-rows-4 mb-8 mx-auto">
            <!-- Cash register date -->
        
            <x-label for="date" :value="__('Fecha')" />
            <x-input type="text" :value="__($date)" readonly />
            
            <!-- Cash register number-->
            <x-label for="cash_register" :value="__('Caja')" />
            <x-select-input 
                :options="$cash_registers_id_arr" 
                :defaultOptTitle="__('Seleccione la caja')"
                id="cash_register_id"
                :name="__('cash_register_id')" 
                :value="old('cash_register_id')" 
                required  
            />
            
            <x-label for="cash_register_worker" :value="__('Cajero/a:')" />
            <x-select-input
                id="cash_register_worker"
                :options="$cash_registers_workers_id_arr"
                :defaultOptTitle="__('Seleccione el cajero/a')"
                :name="__('cash_register_worker')" 
                :value="old('cash_register_worker')"
                required 
            />

            <div class="flex items-center">
                   <x-label for="exist_cash_register_worker" class="basis-2/3" :value="__('No esta registrado el cajero/a ?')" />
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
        </div>
        
        <div class="w-10/12"><h3 class="h3 text-center mb-8">Ingresos en fisico</h3></div>

        <div class="w-10/12 grid gap-4 grid-cols-[150px_auto_150px_auto] mb-8 mx-auto items-center">
            <x-label :value="__('Fecha de registro de la última tasa: ')" />
            <p id="last-dollar-exchange-bs-date">{{ $dollar_exchange?->created_at }}</p>

            <x-label class="w-56" :value="__('Cotización del $:')" />
            <p id="last-dollar-exchange-bs-label">{{ $dollar_exchange?->bs_exchange }} Bs.S</p>
                            

            <!-- Cash on liquid input (dollars) -->
            <x-label :value="__('Total de $ en efectivo:')" />
            <x-input-with-button 
                :inputID="__('total_liquid_money_dollars')"
                :modalID="__('liquid_money_dollars')"
                name="total_liquid_money_dollars"
                type="text"
            />
            <x-label for="liquid_money_dollars_total" :value="__('Total de billetes($) :')" />
            <x-input-with-button 
                :inputID="__('total_liquid_money_dollars_denominations')"
                :modalID="__('liquid_money_dollars_denominations')"
                name="total_liquid_money_dollars_denominations"
                type="text"
            />

            <!-- Cash on liquid input (bolivares) -->
            <x-label :value="__('Total de Bs.S en efectivo:')" />
            <x-input-with-button 
                :inputID="__('total_liquid_money_bolivares')"
                :modalID="__('liquid_money_bolivares')"
                :currency="__('Bs.S')"
                name="total_liquid_money_bolivares"
                type="text"
            />
            <x-label  :value="__('Total de billetes(Bs.S) :')" />
            <x-input-with-button 
                :inputID="__('total_liquid_money_bolivares_denominations')"
                :modalID="__('liquid_money_bolivares_denominations')"
                :currency="__('Bs.S')"
                name="total_liquid_money_bolivares_denominations"
                type="text"
            />
        </div>

        <div class="w-10/12"><h3 class="h3 text-center mb-8">Ingresos en punto de venta</h3></div>

        <div class="w-10/12 grid gap-4 grid-cols-[150px_50px_150px_auto] mb-8 mx-auto items-center">
             <!-- Cash on punto de venta (bs) -->
            <x-label for="debit_card_payment_bs" :value="__('Registros del punto de venta:')" />
            <x-button 
                class="flex justify-center rounded-full h-8 w-8"
                type="button"
                :modalID="__('point_sale_bs')"
            >
                <i class="fas fa-plus text-white"></i>
            </x-button>
            
            <!-- Cash on punto de venta ($) -->
            <x-label for="debit_card_payment_dollar" :value="__('Total de $ en Punto:')" />
            <x-input data-currency="amount-$" placeholder="0.00" id="debit_card_payment_dollar" class="block ml-4" type="text" name="debit_card_payment_dollar" :value="old('debit_card_payment_dollar')" autofocus />
        </div>

        <div class="w-10/12"><h3 class="h3 text-center mb-8">Ingresos en Zelle</h3></div>

        <div class="w-10/12 grid gap-4 grid-cols-[150px_auto] mb-8 mx-auto items-center">
            <x-label :value="__('Total en Zelle:')" />
            <x-input-with-button 
                :inputID="__('total_zelle_record')"
                :modalID="__('zelle_record')"
                name="total_zelle_record"
                type="text"
            />
        </div>

        <div class="w-10/12 flex mx-auto justify-end pt-8">
            <x-button>
                {{ __('Continuar') }}
            </x-button>
        </div>

        <x-modal-input-list 
            :modalID="__('zelle_record')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
        />
        
        <x-modal-input-list 
            :modalID="__('liquid_money_dollars')"
            :currency="config('constants.CURRENCIES.DOLLAR')"
        />
        <x-modal-input-denominations 
            :modalID="__('liquid_money_dollars_denominations')"
            :denominations="['0.50', '1', '2', '5', '10', '20','50', '100']"
        />

        <x-modal-input-list 
            :modalID="__('liquid_money_bolivares')"
            :currency="config('constants.CURRENCIES.BOLIVAR')"
            :isBolivar="true"
        />
        <x-modal-input-denominations 
            :modalID="__('liquid_money_bolivares_denominations')"
            :denominations="['0.50', '1', '2', '5', '10', '20', '50','100', '200', '500']"
            :currency="__('Bs.S')"
        />

        <x-modal-point-sale-list
            :modalID="__('point_sale_bs')"
        />
    </form>
@endsection
