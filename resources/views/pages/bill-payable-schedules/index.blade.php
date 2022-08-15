@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_schedules_index.js') }}" defer></script>
@endsection

@section('main')
    <div>
        <h2 class="h2">Programaciones de facturas por pagar</h2>
        <div class="mb-4 p-4 rounded bg-gray-200 mx-auto w-11/12">
            @include('pages.bill-payable-schedules.components.filter-form')
        </div>

        <div class="mb-8 mx-auto w-11/12">
            <form id="form" class="px-20" autocomplete="off" method="POST" action="{{ route('schedule.store') }}">
                @csrf
                <x-button :variation="__('rounded')">
                    {{ __('Crear programación') }}
                </x-button>
            </form>
        </div>
  
        <table class="table table-bordered table-hover mx-auto w-11/12 text-center">
            <thead class="bg-blue-300">
                <tr>
                    @foreach ($columns as $colum)
                        <th scope="col text-center align-middle">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="billPayableScheduleTBody">
                @foreach($paginator as $key => $value)
                    <a href="#">
                        <tr>
                            <td class="text-center p-0"><a class="block" href="{{ route('schedule.show', $value->WeekNumber) }}">{{ $value->WeekNumber }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('schedule.show', $value->WeekNumber) }}">{{ date('d-m-Y', strtotime($value->StartDate)) }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('schedule.show', $value->WeekNumber) }}">{{ date('d-m-Y', strtotime($value->EndDate)) }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('schedule.show', $value->WeekNumber) }}">{{ $value->Status }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('schedule.show', $value->WeekNumber) }}">{{ $value->QtyBillsScheduled }}</a></td>
                            <td>&nbsp;</td>
                        </tr>
                    </a>
                @endforeach
            </tbody>
        </table>
        <div class="w-11/12 mx-auto">
            @if ($paginator->count() === 0)
                <p class="text-center">No hay registros</p>
            @endif
            <div class="mt-8 pb-32">
                {{ $paginator->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    <x-modal-loading :title="__('Espere mientras se genera el documento PDF')"/>
@endsection