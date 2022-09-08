@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_schedules_index.js') }}" defer></script>
@endsection

@section('main')
    <div class="mx-auto w-11/12">
        <div class="mb-8">
            <h2 class="h2">Programación con sus facturas</h2>
        </div>

        <div class="w-5/6 mx-auto p-3 bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
            <div class="flex mb-4">
                <h4 class="h4">Datos de la programación</h4>
            </div>
             
            <div class="flex mb-4">
                <div>
                    <span class="font-semibold">Nro. de semana: </span>
                    <p>{{ $bill_payable_schedule->id }}</p>
                </div>

                <div class="ml-8">
                    <span class="font-semibold">Fecha Inicio:</span>
                    <p>{{ date('d-m-Y', strtotime($bill_payable_schedule->start_date)) }}</p>
                </div>
                
                <div class="ml-8">
                    <span class="font-semibold">Fecha Final:</span>
                    <p>{{ date('d-m-Y', strtotime($bill_payable_schedule->end_date)) }}</p>
                </div>

                <div class="ml-8">
                    <span class="font-semibold">Estatus:</span>
                    <p>{{ config("constants.BILL_PAYABLE_SCHEDULE_STATUS." . $bill_payable_schedule->status) }}</p>
                </div>
            </div>
            
        </div>

        <div class="w-5/6 mx-auto p-4 py-6 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 mb-52">
            <div class="flex mb-4">
                <h4 class="h4">Facturas en Bolivares</h4>
            </div>
            <table class="table table-bordered table-hover mx-auto text-center p-0 mb-8">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($columns as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @foreach($bs_bill_payable as $bill)
                        <tr>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ $bill->NumeroD }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ $bill->Descrip }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->Tasa, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->MontoPagado, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ config("constants.BILL_PAYABLE_STATUS." . $bill->Status) }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex mb-4">
                <h4 class="h4">Facturas en Dolares</h4>
            </div>
            <table class="table table-bordered table-hover mx-auto text-center p-0">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($columns as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @foreach($dollar_bill_payable as $bill)
                        <tr>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ $bill->NumeroD }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ $bill->Descrip }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->Tasa, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ number_format($bill->MontoPagado, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->NumeroD, 'cod_prov' => $bill->CodProv]) }}">{{ config("constants.BILL_PAYABLE_STATUS." . $bill->Status) }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection