@extends('layouts.app')

@section('main')
    @foreach ($errors->all() as $error)
        <li>
            {{ $error }}
        </li>
    @endforeach
    <form id="form" method="POST" action="{{ route('cash_register_worker.store') }}">
        @csrf
        
        <div class="w-10/12 flex justify-center mb-4 mx-auto">
            <!-- Cash on liquid input (dollars) -->
            <div class="flex basis-1/4 items-center">
                    <x-label for="liquid_money_dollars" :value="__('Total de $ en efectivo:')" />
                    <x-input data-currency="amount-$" placeholder="0.00" id="liquid_money_dollars" class="block ml-4 pr-8" type="text" name="liquid_money_dollars"  autofocus />
            </div>
        </div>
        <div class="w-10/12 flex mx-auto justify-end pt-8">
            <x-button>
                {{ __('Registrar') }}
            </x-button>
        </div>
        
    </form>
@endsection
