@extends('layouts.app')

@section('main')
    
    <div class="w-10/12 mx-auto">
        <h3 class="h3 text-center mb-4">{{ $title }}</h3>
        <x-table :columns="$columns" :data="$bills" :total="$sum_amount" :cash_register="$cash_register"/>
        <div class="w-10/12 flex mx-auto justify-end pt-8">
            <x-nav-link-button href="{{ route('cash_register_step_five.create') }}">
                Regresar
            </x-nav-link-button>
            <form method="POST" action="{{ route('cash_register.store') }}">
                @csrf
                <x-button class="ml-4">
                    Completar
                </x-button>
            </form>
        </div>
    </div>
@endsection