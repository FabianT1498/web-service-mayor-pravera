@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_schedules_index.js') }}" defer></script>
@endsection

@section('main')
    <div>
        <h2 class="h2">Programaciones de facturas por pagar</h2>
        <div class="mb-8 p-4 rounded bg-gray-200 mx-auto w-11/12">
            @include('pages.bill-payable-schedules.components.filter-form')
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
                    <tr data-numeroD="{{ $value->NumeroD }}" data-prov="{{ $value->CodProv }}">
                        <td class="text-center">{{ $value->WeekNumber }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->StartDate)) }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->EndDate)) }}</td>
                        <td class="text-center">{{ $value->Status }}</td>
                        <td class="text-center">{{ $value->QtyBillsScheduled }}</td>
                    </tr>
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