@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_index.js') }}" defer></script>
@endsection

@section('main')
    <div class="relative">

        <h2 class="h2">Facturas por pagar</h2>
        <div class="mb-8 p-4 rounded bg-gray-200 mx-auto w-11/12">
            @include('pages.bills-payable.components.filter-form')
        </div>

        <div class="mx-auto w-1/3 top-3 right-3 mb-8 absolute">
            <x-alert-flash
                :alertID="__('bill-payable-alert')"
                :message="__('Las facturas que esta agrupando tienen distintos proveedores')"
            />
        </div>

        <div id="linkBillsPayableContainer" class="hidden mx-auto w-11/12 mb-8">
            <x-button 
                :variation="__('rounded')"
                :type="__('button')"
            >
                    {{ __('Agrupar facturas') }}
            </x-button>
        </div>
       
        <table id="billsTable" class="table table-bordered table-hover mx-auto w-11/12 text-center">
            <thead class="bg-blue-300">
                <tr>
                    <th scope="col" class="text-center align-middle">
                        <input
                            class="form-checkbox w-4 h-4 text-blue-600 rounded  focus:ring-blue-500 focus:ring-2 {{ count($data) === 0 ? "bg-gray-100 border-gray-300" : ""}}"
                            type="checkbox"
                            id="checkBoxSelectAll"              
                            {{ count($data) === 0 ? 'disabled' : '' }}
                            @if (count($data) === 0) 
                                onclick= "return false;" 
                            @endif
                        />
                    </th>
                    @foreach ($columns as $colum)
                        <th scope="col" class="text-center align-middle">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="billsPayableTBody">
                @foreach ($data as $key => $value)
                    <tr 
                        class="{{ $value->BillPayableGroupsID ? "bg-gray-300 hover:!bg-slate-300" : "" }}"
                        data-numeroD="{{ $value->NumeroD }}" data-prov="{{ $value->CodProv }}" 
                        data-descripProv="{{ $value->Descrip }}"
                        @if ($value->BillPayableGroupsID)
                            data-tooltip-target="grouped-bill"
                        @endif
                    >
                        <td>
                            <input
                                class="form-checkbox w-4 h-4 text-blue-600 rounded  focus:ring-blue-500 focus:ring-2 {{ $value->MontoPagado > 0.00 ? "bg-gray-100 border-gray-300" : ""}}"
                                type="checkbox"
                                value="0"
                                data-bill="select"              
                                {{ ($value->MontoPagado > 0.00 || (is_null($value->BillPayableGroupsID) && !is_null($value->BillPayableSchedulesID))) ? 'disabled' : '' }}
                                @if ($value->MontoPagado > 0.00 || (is_null($value->BillPayableGroupsID) && !is_null($value->BillPayableSchedulesID))) 
                                    onclick= "return false;" 
                                @endif
                            />
                            @if ($value->BillPayableGroupsID)
                                <div 
                                    id="grouped-bill"
                                    role="tooltip" 
                                    class="inline-block absolute invisible z-10 py-2 px-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                >
                                    Factura agrupada en lote
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            @endif
                        </td>
                        <th scope="row" class="text-center">
                            <a class="block relative" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}"
                            >
                                @if ($value->BillPayableSchedulesID)
                                    <div class="absolute top-0 right-2">
                                        <span
                                            data-tooltip-target="scheduled-bill"
                                            class="absolute top-0 right-0 h-2 w-2 transform bg-red-600 rounded-full motion-safe:animate-pulse">
                                                &nbsp;
                                        </span>
                                        <div 
                                            id="scheduled-bill"
                                            role="tooltip" 
                                            class="inline-block absolute invisible z-10 w-28 py-2 px-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                                        >
                                            Factura programada
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div> 
                                    </div>
                                @endif
                                {{ $value->NumeroD }}
                            </a>
                        </th>
                        <td class="text-center"><a class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ $value->CodProv }}</a></td>
                        <td class="text-center"><a class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ $value->Descrip }}</a></td>
                        <td class="text-center"><a data-bill="fechaE" class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ date('d-m-Y', strtotime($value->FechaE)) }}</a></td>
                        <td class="text-center"><a class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ date('d-m-Y', strtotime($value->FechaPosteo)) }}</a></td>
                        <td class="text-center"><a data-bill="montoTotal" class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ $value->MontoTotal }}</a></td>
                        <td class="text-center"><a data-bill="montoPagar" class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ $value->MontoPagar }}</a></td>
                        <td class="text-center">
                            @if ($value->MontoPagado === 0.00)
                                <input 
                                    class="{{ 'w-32 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                        ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                                    }}"
                                    type="text" 
                                    id="tasa" 
                                    value="{{ $value->Tasa }}"
                                    data-bill="tasa"
                                >
                            @else
                                <a 
                                    data-bill="tasa" 
                                    class="block" 
                                    href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}"
                                >
                                    {{ $value->Tasa . ' ' . config("constants.CURRENCY_SIGNS.bolivar") }}
                                </a>
                            @endif
                        </td>
                        <td class="text-center">
                            <input
                                class="form-checkbox w-4 h-4 text-blue-600 rounded  focus:ring-blue-500 focus:ring-2 {{ $value->Tasa === 0.00 ? "bg-gray-100 border-gray-300" : ""}}"
                                type="checkbox"       
                                value="{{ $value->esDolar }}"               
                                {{ $value->esDolar ? "checked" : "" }}
                                data-bill="isDollar"
                                {{ $value->MontoPagado === 0.00 ? '' : 'disabled' }}
                                @if ($value->Tasa === 0.00 || $value->MontoPagado > 0.00) 
                                    onclick= "return false;" 
                                @endif
                            />
                        </td>
                        <td class="text-center" ><a class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ $value->Estatus }}</a></td>
                        <td class="text-center" ><a class="block" href="{{ 
                                $value->BillPayableGroupsID ? route('bill_payable.showBillPayableGroup', ['id' => $value->BillPayableGroupsID]) 
                                : ($value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#') }}">{{ $value->DiasTranscurridos }}</a></td>
                        <td>
                            @if ($value->Estatus === config("constants.BILL_PAYABLE_STATUS.NOTPAID") && $value->MontoPagado === 0.00 && is_null($value->BillPayableGroupsID))
                                <button
                                    type="button"
                                    data-tooltip-target="suggestion-tooltip"
                                    data-modal-toggle="bill_payable_schedules"
                                    data-bill="modalBtn"
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
    <x-modal-loading/>
    <x-modal-bill-payable-schedules
        :modalID="__('bill_payable_schedules')"
        :schedules="$schedules"
    />
    <x-modal-bill-payable-group
        :modalID="__('billPayableGroupModal')"
        :transparent="true"
    />
@endsection