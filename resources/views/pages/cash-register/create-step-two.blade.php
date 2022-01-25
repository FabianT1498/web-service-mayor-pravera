@extends('layouts.app')

@section('main')
    
    <div>
        <x-table :columns="$columns" :data="$bills"/>
        <div class="w-10/12 flex mx-auto justify-end pt-8">
            <x-button>
                {{ __('Continuar') }}
            </x-button>
        </div>
    </div>
@endsection
