@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_schedules_index.js') }}" defer></script>
@endsection

@section('main')
    <div class="mx-auto w-11/12">
        <h2 class="h2">Programación con sus facturas</h2>

        <div class="w-1/2 mx-auto text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200">
            <table class="table table-bordered table-hover mx-auto text-center p-0">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($columns as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @foreach($bills as $bill)
                        <tr>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->nro_doc, 'cod_prov' => $bill->cod_prov]) }}">{{ $bill->nro_doc }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->nro_doc, 'cod_prov' => $bill->cod_prov]) }}">{{ $bill->descrip_prov }}</a></td>
                            <td class="text-center p-0"><a class="block" href="{{ route('bill_payable.showBillPayable', ['numero_d' => $bill->nro_doc, 'cod_prov' => $bill->cod_prov]) }}">{{ $bill->amount }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection