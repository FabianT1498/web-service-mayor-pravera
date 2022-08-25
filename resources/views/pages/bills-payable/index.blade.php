@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_index.js') }}" defer></script>
@endsection

@section('main')
    <div>
        <h2 class="h2">Facturas por pagar</h2>
        <div class="mb-8 p-4 rounded bg-gray-200 mx-auto w-11/12">
            @include('pages.bills-payable.components.filter-form')
        </div>

        <div class="mx-auto w-11/12">
            <x-alert 
                :alertID="__('bill-payable-alert')"
                :message="__('Debe ingresar una tasa mayor que cero')"
            />
        </div>
       
        <table class="table table-bordered table-hover mx-auto w-11/12 text-center">
            <thead class="bg-blue-300">
                <tr>
                    @foreach ($columns as $colum)
                        <th scope="col text-center align-middle">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="billsPayableTBody">
                @foreach ($data as $key => $value)
                    <tr data-numeroD="{{ $value->NumeroD }}" data-prov="{{ $value->CodProv }}" data-descripProv="{{ $value->Descrip }}">
                        <td class="text-center">
                            <a class="block relative" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">
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
                        </td>
                        <td class="text-center"><a class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ $value->CodProv }}</a></td>
                        <td class="text-center"><a class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ $value->Descrip }}</a></td>
                        <td class="text-center"><a data-bill="fechaE" class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ date('d-m-Y', strtotime($value->FechaE)) }}</a></td>
                        <td class="text-center"><a class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ date('d-m-Y', strtotime($value->FechaPosteo)) }}</a></td>
                        <td class="text-center"><a data-bill="montoTotal" class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ $value->MontoTotal }}</a></td>
                        <td class="text-center"  data-bill="montoPagar"><a class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ $value->MontoPagar }}</a></td>
                        <td class="text-center">
                            <input 
                                class="{{ 'w-32 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                                }}"
                                type="text" 
                                id="tasa" 
                                value="{{ $value->Tasa }}"
                                data-bill="tasa"
                            >
                        </td>
                        <td class="text-center">
                            <input
                                class="form-checkbox w-4 h-4 text-blue-600 rounded  focus:ring-blue-500 focus:ring-2 {{ number_format($value->Tasa, 2) === '0.00' ? "bg-gray-100 border-gray-300" : ""}}"
                                type="checkbox"       
                                value="{{ $value->esDolar }}"               
                                {{ $value->esDolar ? "checked" : "" }}
                                data-bill="isDollar"
                                @if ($value->Tasa === "0.00") 
                                    onclick= "return false;" 
                                @endif
                            />   
                        </td>
                        <td class="text-center" ><a class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ $value->Estatus }}</a></td>
                        <td class="text-center" ><a class="block" href="{{ $value->BillPayableSchedulesID ? route('bill_payable.showBillPayable', ['numero_d' => $value->NumeroD, 'cod_prov' => $value->CodProv]) : '#' }}">{{ $value->DiasTranscurridos }}</a></td>
                        <td>
                            @if ($value->Estatus === config("constants.BILL_PAYABLE_STATUS.NOTPAID"))
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
    <x-modal-loading :title="__('Espere mientras se genera el documento PDF')"/>
    <x-modal-bill-payable-schedules
        :modalID="__('bill_payable_schedules')"
        :schedules="$schedules"
    />
@endsection