@extends('layouts.app')

@section('main')
    <div class="w-10/12 mx-auto">
        <x-table 
            :columns="$columns" 
            :data="$records"
            :hasOptions="true"
        >
            <table-data-options/>
        </x-table>
    </div>
@endsection