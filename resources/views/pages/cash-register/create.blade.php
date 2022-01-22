@extends('layouts.app')

@section('main')
    <form method="POST" action="">
        @csrf
        <div class="w-10/12 flex justify-between mb-4 mx-auto">
            <!-- Cash register date -->
            <div class="flex basis-1/4 justify-evenly items-center">
                <x-label for="date" :value="__('Fecha')" />

                <x-input id="date" class="disabled ml-4" type="date" name="date" :value="old('date') ? old('date') : $date" required autofocus/>
            </div>
            
            <!-- Cash register number-->
            <div class="flex basis-1/4 items-center">
                <x-label for="cash_register" :value="__('Caja')" />

                <x-select-input :options="$cash_registers_id" id="cash_register" class="block ml-4" name="cash_register_id" :value="old('cash_register_id')" required autofocus />
            </div>
            
            <!-- Cash register owner -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="cash_register_owner" :value="__('Nombre del cajero/a:')" />

                    <x-input id="cash_register_owner" class="block ml-4" type="text" name="cash_register_owner" :value="old('cash_register_owner')" required autofocus />
            </div>
        </div>

        <div class="w-10/12 flex justify-center mb-4">
            <!-- Cash register date -->
            <div class="flex basis-1/4 items-center">
                <x-label for="cash_amount" :value="__('Monto efectivo (Bs.s):')" />

                <x-input id="cash_amount" class="block disabled" type="text" name="cash_amount_bs" :value="old('cash_amount_bs')" required />
            </div>
        </div>
        
    </form>
@endsection
