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
       
        <table class="table table-bordered table-hover mx-auto w-11/12 text-center">
            <thead class="bg-blue-300">
                <tr>
                    @foreach ($columns as $colum)
                        <th scope="col text-center align-middle">{{ $colum }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody id="billsPayableTBody">
                @foreach ($paginator as $key => $value)
                    <tr>
                        <td class="text-center">{{ $value->NumeroD }}</td>
                        <td class="text-center">{{ $value->CodProv }}</td>
                        <td class="text-center">{{ $value->Descrip }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->FechaE)) }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($value->FechaPosteo)) }}</td>
                        <td class="text-center">{{ number_format($value->MontoTotal, 2) }}</td>
                        <td class="text-center">{{ number_format($value->MontoPagar, 2) }}</td>
                        <td class="text-center">
                            <input
                                class="form-checkbox"
                                type="checkbox"                      
                                {{$value->esDolar ? "checked" : "" }}
                            />   
                        </td>
                        <td>
                            <button
                                type="button"
                                data-tooltip-target="suggestion-tooltip"
                                data-modal-toggle="suggestion-modal"
                                data_numero_d="{{ $value->NumeroD }}"
                                class="font-medium hover:text-teal-600 transition ease-in-out duration-500"
                            >
                                <i class="fa-solid fa-calendar"></i>
                            </button>
                            <div 
                                id="suggestion-tooltip"
                                role="tooltip" 
                                class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
                            >
                                Programar pago
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div> 
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
@endsection