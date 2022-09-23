@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bill_payable_groups_index.js') }}" defer></script>
@endsection

@section('main')
    <div class="relative">

        <h2 class="h2">Lotes de facturas por pagar</h2>
        <div class="mb-8 p-4 rounded bg-gray-200 mx-auto w-11/12">
            @include('pages.bill-payable-groups.components.filter-form')
        </div>

        <table id="billsTable" class="table table-bordered table-hover mx-auto w-11/12 text-center">
            <thead class="bg-blue-300">
                <tr>
                    @foreach ($columns as $colum)
                        <th scope="col" class="text-center align-middle">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="billsPayableTBody">
                @foreach ($data as $key => $value)
                    <tr 
                        data-id="{{ $value->ID }}"
                        @if ($value->MontoTotal === 0.00)
                            data-tooltip-target="no-bills-payable-tooltip"
                        @endif
                    >
                        <th scope="row" class="text-center">
                            <a class="block relative" href="{{ $value->MontoTotal > 0 ? route('bill_payable.showBillPayableGroup', ['id' => $value->ID]) : '#' }}">
                                {{ $value->ID }}
                            </a>
                            @if ($value->MontoTotal === 0.00)
                                <div 
                                    id="no-bills-payable-tooltip"
                                    role="tooltip" 
                                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                >
                                    No hay facturas asociadas a este lote
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            @endif
                        </th>
                        <td class="text-center"><a class="block" href="{{ $value->MontoTotal > 0 ? route('bill_payable.showBillPayableGroup', ['id' => $value->ID]) : '#' }}">{{ $value->DescripProv }}</a></td>
                        <td class="text-center"><a data-bill="montoTotal" class="block" href="{{ $value->MontoTotal > 0 ? route('bill_payable.showBillPayableGroup', ['id' => $value->ID]) : '#' }}">{{ $value->MontoTotal . " " . config("constants.CURRENCY_SIGNS.dollar") }}</a></td>
                        <td class="text-center"><a data-bill="montoPagar" class="block" href="{{ $value->MontoTotal > 0 ? route('bill_payable.showBillPayableGroup', ['id' => $value->ID]) : '#' }}">{{ $value->MontoPagado . " " . config("constants.CURRENCY_SIGNS.dollar") }}</a></td>
                        <td class="text-center" ><a class="block" href="{{ $value->MontoTotal > 0 ? route('bill_payable.showBillPayableGroup', ['id' => $value->ID]) : '#' }}">{{ $value->Estatus }}</a></td>
                        <td>
                            @if ( $value->Estatus === config("constants.BILL_PAYABLE_STATUS.NOTPAID") && $value->MontoPagado === 0.00)
                                <button
                                    type="button"
                                    data-tooltip-target="suggestion-tooltip"
                                    data-modal-toggle="bill_payable_schedules"
                                    data-groupID="{{ $value->ID }}"
                                    class="font-medium hover:text-teal-600 transition ease-in-out duration-500"
                                >
                                    <i class="fa-solid fa-calendar"></i>
                                </button>
                                <div 
                                    id="suggestion-tooltip"
                                    role="tooltip" 
                                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                >
                                    Programar factura
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            @else
                                &nbsp;
                            @endif
                        </td>
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
   
    <x-modal-bill-payable-schedules
        :isGroup="true"
        :modalID="__('bill_payable_schedules')"
        :schedules="$schedules"
    />

@endsection