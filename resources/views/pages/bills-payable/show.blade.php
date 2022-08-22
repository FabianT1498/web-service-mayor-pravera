@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_show.js') }}" defer></script>
@endsection

@section('main')
    <div class="mx-auto w-11/12">
        <div class="mb-8">
            <h2 class="h2">Pagos de la factura</h2>
        </div>

        <div class="w-4/6 mx-auto p-4 bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
            <div class="flex mb-4">
                <h4 class="h4">Datos de la factura</h4>
            </div>
            <div class="flex">
                <p><span class="font-semibold">Monto total: </span>{{ number_format($bill->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS." . ($bill->esDolar ? "dollar" : "bolivar")) }}</p>
                <p class="ml-8"><span class="font-semibold">Monto por pagar: </span>{{ number_format($bill->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS." . ($bill->esDolar ? "dollar" : "bolivar")) }}</p>
                <p class="ml-8"><span class="font-semibold">Nro Factura: </span>{{$bill->NumeroD}}</p>
                <p class="ml-8"><span class="font-semibold">Proveedor: </span>{{$bill->DescripProv}}</p>
            </div>
        </div>

        <div class="w-4/6 mx-auto text-sm font-medium text-gray-900 mb-8">
            <div class="mb-4">
                <x-button id="toggleFormBtn" class="justify-center" :variation="__('rounded')">
                    <p><span>Ocultar</span>&nbsp;formulario</p>
                </x-button>
            </div>
            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                @include('pages.bills-payable.components.create-payment-form')
            </div>
        </div>

        @if ($errors->count() > 0)
            <div class="w-4/6 p-4 mx-auto text-gray-900 mb-8 bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="mb-2">
                    <h3 class="h3 mb-0">Erores</h3>
                </div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li class="list-none error">{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8">
            <h3 class="h3">Pagos en Bs</h3>
        </div>

        <div class="w-1/2 mx-auto text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 mb-8">
            <table class="table table-bordered table-hover mx-auto text-center p-0">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($payment_bs_table_cols as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @if ($bill_payments_bs->count() > 0)

                        @foreach($bill_payments_bs as $bill_payment)
                            <tr>
                                <td class="text-center p-0">{{ $bill_payment->Date }}</td>
                                <td class="text-center p-0">{{ $bill_payment->BankName }}</td>
                                <td class="text-center p-0">{{ $bill_payment->RefNumber }}</td>
                                <td class="text-center p-0">{{ $bill_payment->Tasa }}</td>
                                <td class="text-center p-0">{{ $bill_payment->Amount }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>No hay elementos</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>   
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <h3 class="h3">Pagos en Dolares</h3>
        </div>

        <div class="w-1/2 mx-auto text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 mb-40">
            <table class="table table-bordered table-hover mx-auto text-center p-0">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($payment_dollar_table_cols as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @if ($bill_payments_dollar->count() > 0)
                        @foreach($bill_payments_dollar as $bill_payment)
                            <tr>
                                <td class="text-center p-0">{{ $bill_payment->Date }}</td>
                                <td class="text-center p-0">{{ $bill_payment->PaymentMethod }}</td>
                                <td class="text-center p-0">{{ $bill_payment->RetirementDate }}</td>
                                <td class="text-center p-0">{{ $bill_payment->Amount }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>&nbsp;</td>
                            <td>No hay elementos</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>   
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection