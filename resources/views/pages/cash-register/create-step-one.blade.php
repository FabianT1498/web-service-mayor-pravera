@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/cash_register_create.js') }}" defer></script>
@endsection

@section('main')
    <form method="POST" action="{{ route('cash_register_step_one.post') }}">
        @csrf
        <div class="w-10/12 flex justify-between mb-4 mx-auto">
            <!-- Cash register date -->
            <div class="flex basis-1/4 justify-evenly items-center">
                <x-label for="date" :value="__('Fecha')" />

                <x-input id="date" class="ml-4" type="text" :value="__($date)" :disabled="true" name="date"  required autofocus/>
            </div>
            
            <!-- Cash register number-->
            <div class="flex basis-1/4 items-center">
                <x-label for="cash_register" :value="__('Caja')" />

                <x-select-input :options="$cash_registers_id" id="cash_register" class="block ml-4" name="cash_register_id" :value="old('cash_register_id')" required autofocus />
            </div>
            
            <!-- Cash register owner -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="cash_register_owner" :value="__('Nombre del cajero/a:')" />
                    <x-input id="cash_register_owner" class="block ml-4" type="text" name="cash_register_worker" :value="old('cash_register_owner')" required autofocus />
            </div>
        </div>

        <div class="w-10/12 flex justify-center mb-4 mx-auto">
            <!-- Cash on liquid input (dollars) -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="liquid_money_dollars" :value="__('Total de $ en efectivo:')" />
                    <x-input data-currency="amount-$" id="liquid_money_dollars" class="block ml-4 pr-8" type="text" name="liquid_money_dollars" :value="old('liquid_money_dollars')" required autofocus />
            </div>

            <!-- Cash on liquid input (bs) -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="liquid_money_bs" :value="__('Total de Bs.s en efectivo:')" />

                    <x-input data-currency="amount-bs" id="liquid_money_bs" class="block ml-4" type="text" name="liquid_money_bs" :value="old('liquid_money_bs')" required autofocus />
            </div>

            <!-- Cash on zelle -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="payment_zelle" :value="__('Total de $ en Zelle:')" />

                    <x-input data-currency="amount-$" id="payment_zelle" class="block ml-4" type="text" name="payment_zelle" :value="old('payment_zelle')" required autofocus />
            </div>
        </div>

        <div class="w-10/12 flex mx-auto">
             <!-- Cash on punto de venta (bs) -->
             <div class="flex basis-1/4 items-center">
                    <x-label for="debit_card_payment_bs" :value="__('Total de Bs en Punto:')" />

                    <x-input data-currency="amount-bs" id="debit_card_payment_bs" class="block ml-4" type="text" name="debit_card_payment_bs" :value="old('debit_card_payment_bs')" required autofocus />
            </div>

            <!-- Cash on punto de venta internacional (dollars) -->
            <div class="flex basis-1/4 items-center ml-4">
                    <x-label for="debit_card_payment_dollar" :value="__('Total de $ en Punto:')" />

                    <x-input data-currency="amount-$" id="debit_card_payment_dollar" class="block ml-4" type="text" name="debit_card_payment_dollar" :value="old('debit_card_payment_dollar')" required autofocus />
            </div>
        </div>

        <div class="w-10/12 flex mx-auto justify-end pt-8">
            <x-button>
                {{ __('Continuar') }}
            </x-button>
        </div>
        
    </form>
@endsection
