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
        <div class="w-10/12 grid gap-4 grid-cols-[200px_250px] grid-rows-4 mb-8 mx-auto">
            <!-- Cash register date -->
        
            <x-label for="date" :value="__('Fecha')" />
            <x-input id="date" type="text" :value="__($date)" readonly />
            
            <!-- Cash register number-->
            <x-label for="cash_register" :value="__('Caja')" />
            <x-select-input 
                :options="$cash_registers_id_arr" 
                :defaultOptTitle="__('Seleccione la caja')"
                id="cash_register" 
                :name="__('cash_register_id')" 
                :value="old('cash_register_id')" 
                required  
            />
            
            <x-label for="cash_register_worker" :value="__('Cajero/a:')" />
            <x-select-input 
                :options="$cash_registers_workers_id_arr"
                :defaultOptTitle="__('Seleccione el cajero/a')"
                id="cash_register_worker" 
                :name="__('cash_register_worker')" 
                :value="old('cash_register_worker')" 
                required 
            />

            <div class="flex items-center">
                   <x-label for="exist_cash_register_worker" class="basis-2/3" :value="__('No esta registrado el cajero/a ?')" />
                   <input id="exist_cash_register_worker" type="checkbox" name="exist_cash_register_worker" value="{{ old('exist_cash_register_worker') ? old('exist_cash_register_worker') : 0 }}" />
           </div>
           <div id="hidden-new-cash-register-worker-container" class="hidden">
                    <x-input 
                        id="new_cash_register_worker" 
                        placeholder="Nombre del cajero"
                        class="w-full"
                        type="text" 
                        name="new_cash_register_worker" 
                        :value="old('new_cash_register_worker') ? old('new_cash_register_worker') : ''" 
                    />
            </div>
        </div>
        
        <div class="w-10/12"><h3 class="h3 text-center mb-8">Datos de ingresos</h3></div>

        <div class="w-10/12 grid gap-4 grid-cols-[150px_auto_auto_auto] grid-rows-4 mb-8 mx-auto">
            <!-- Cash on liquid input (dollars) -->
            <x-label for="liquid_money_dollars" :value="__('Total de $ en efectivo:')" />
            <x-input-with-button 
                id="total_liquid_money_dollar" 
                name="liquid_money_dollars"
                type="text"
                :modalID="__('authentication-modal')"
            />

            <!-- Cash on liquid input (bs) -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="liquid_money_bs" :value="__('Total de Bs.s en efectivo:')" />

                    <x-input data-currency="amount-bs" placeholder="0.00" id="liquid_money_bs" class="block ml-4" type="text" name="liquid_money_bs" :value="old('liquid_money_bs')" autofocus />

                    <!-- <x-input data-currency="amount-bs" placeholder="0.00" id="liquid_money_bs" class="block ml-4" type="text" name="liquid_money_bs" :value="old('liquid_money_bs')" autofocus /> -->
            </div>

            <!-- Cash on zelle -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="payment_zelle" :value="__('Total de $ en Zelle:')" />

                    <x-input data-currency="amount-$" placeholder="0.00" id="payment_zelle" class="block ml-4" type="text" name="payment_zelle" :value="old('payment_zelle')" autofocus />
            </div>
        </div>

        <div class="w-10/12 flex mx-auto">
             <!-- Cash on punto de venta (bs) -->
             <div class="flex basis-1/4 items-center">
                    <x-label for="debit_card_payment_bs" :value="__('Total de Bs en Punto:')" />

                    <x-input data-currency="amount-bs" placeholder="0.00" id="debit_card_payment_bs" class="block ml-4" type="text" name="debit_card_payment_bs" :value="old('debit_card_payment_bs')" autofocus />
            </div>

            <!-- Cash on punto de venta internacional (dollars) -->
            <div class="flex basis-1/4 items-center ml-4">
                    <x-label for="debit_card_payment_dollar" :value="__('Total de $ en Punto:')" />

                    <x-input data-currency="amount-$" placeholder="0.00" id="debit_card_payment_dollar" class="block ml-4" type="text" name="debit_card_payment_dollar" :value="old('debit_card_payment_dollar')" autofocus />
            </div>
        </div>

        <div class="w-10/12 flex mx-auto justify-end pt-8">
            <x-button>
                {{ __('Continuar') }}
            </x-button>
        </div>
        
        <x-modal-input-list :modalID="__('authentication-modal')"/>
    </form>
@endsection
