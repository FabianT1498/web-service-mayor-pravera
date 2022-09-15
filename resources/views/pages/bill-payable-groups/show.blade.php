@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bill_payable_group_show.js') }}" defer></script>
@endsection

@section('main')
    <div class="mx-auto w-11/12">
        <div class="mb-8">
            <h2 class="h2">Pagos de la factura</h2>
        </div>

        <div class="w-10/12 mx-auto p-6 bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
            <div class="flex mb-4">
                <h4 class="h4">Datos del lote de facturas</h4>
            </div>
            <div>
                <form action="{{ route('bill_payable_groups.update-tasa') }}" method="POST" class="w-full">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{$group->ID}}" name="group_id">
                    
                    <div class="flex mb-4">
    
                        <div>
                            <span class="font-semibold">Monto total ($): </span>
                            <p>{{ number_format($group->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</p>
                        </div>
                       
                        <div class="ml-8">
                            <span class="font-semibold">Monto por pagar ($):</span>
                            <p>{{ number_format($group->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</p>
                        </div>
    
                        <div class="ml-8">
                            <span class="font-semibold">Nro. de lote: </span>
                            <p>{{$group->ID}}</p>
                        </div>

                    </div>
                    <div class="flex mb-4">
                        <div class="flex items-center w-3/4">
                            
                            <div class="w-2/6">
                                <span class="font-semibold">Proveedor: </span>
                                <p>{{$group->DescripProv}}</p>
                            </div>
        
                            <div class="ml-8 w-2/6">
                                <span class="font-semibold block mb-2">Tasa: </span>
                                @if (config("constants.BILL_PAYABLE_STATUS." . $group->Estatus) === config("constants.BILL_PAYABLE_STATUS.NOTPAID") && $group->MontoPagado === 0.00)
                                    <input 
                                        class="{{ 'border w-full  border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                            ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                                        }}"
                                        type="text"
                                        name="group_tasa"
                                        id="groupTasa" 
                                        value="{{ old('group_tasa') ? old('group_tasa') : $last_tasa->Tasa }}"
                                        required
                                    >
                                @else
                                   <p>{{ $last_tasa->Tasa . ' ' . config("constants.CURRENCY_SIGNS.bolivar") }}</p>
                                @endif
                            </div>
                        </div>
                        
                        @if (config("constants.BILL_PAYABLE_STATUS." . $group->Estatus) === config("constants.BILL_PAYABLE_STATUS.NOTPAID") && $group->MontoPagado === 0.00)
                            <div class="ml-8 flex flex-col justify-end">
                                <x-button 
                                    :variation="__('rounded')"  
                                >
                                    <span>Actualizar tasa</span>
                                </x-button>
                            </div>
                        @endif
                    </div>

                    @if ($group->MontoPagado > 0.00 || config("constants.BILL_PAYABLE_STATUS." . $group->Estatus) === config("constants.BILL_PAYABLE_STATUS.PAID"))
                        <p class="font-semibold">
                            {{ config("constants.BILL_PAYABLE_STATUS." . $group->Estatus) === config("constants.BILL_PAYABLE_STATUS.PAID")
                                ? 'No puede cambiar la tasa, ya el lote ha sido pagado.'
                                : ( $group->MontoPagado > 0.00
                                    ? 'No puede cambiar la tasa, ya el lote tiene pagos.'
                                    : '')
                            }}
                        </p>
                    @endif
                    
                    @if ($errors->first('group_tasa'))
                        <div>
                            <div class="mb-2 flex">
                                <h4 class="h4 mb-0">Errores</h4>
                            </div>
                            <ul>
                                <li class="list-none error">{{$errors->first('group_tasa')}}</li>
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="w-10/12 mx-auto p-6 bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
            <div class="flex mb-4">
                <h4 class="h4">Facturas agrupadas</h4>
            </div>
            <table class="table table-bordered table-hover mx-auto text-center p-0">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($bills_payable_columns as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @if ($bs_bills_payable->count() > 0)
                        <tr class="text-center"><th colspan="{{ count($bills_payable_columns) }}">Facturas en Bolivares</th></tr>
                        @foreach($bs_bills_payable as $bill)
                            <tr>
                                <td class="text-center p-0">{{ $bill->NumeroD }}</td>
                                <td class="text-center p-0">{{ $bill->Descrip }}</td>
                                <td class="text-center p-0">{{ number_format($bill->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                <td class="text-center p-0">{{ number_format($bill->Tasa, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                <td class="text-center p-0">{{ number_format($bill->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                <td class="text-center p-0">{{ number_format($bill->MontoPagado, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                <td class="text-center p-0">{{ config("constants.BILL_PAYABLE_STATUS." . $bill->Status) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    @if ($dollar_bills_payable->count() > 0)
                        <tr class="text-center"><th colspan="{{ count($bills_payable_columns) }}">Facturas en Dolares</th></tr>
                        @foreach($dollar_bills_payable as $bill)
                            <tr>
                                <td class="text-center p-0">{{ $bill->NumeroD }}</td>
                                <td class="text-center p-0">{{ $bill->Descrip }}</td>
                                <td class="text-center p-0">{{ number_format($bill->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                <td class="text-center p-0">{{ number_format($bill->Tasa, 2) . " " . config("constants.CURRENCY_SIGNS.bolivar") }}</td>
                                <td class="text-center p-0">{{ number_format($bill->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                <td class="text-center p-0">{{ number_format($bill->MontoPagado, 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</td>
                                <td class="text-center p-0">{{ config("constants.BILL_PAYABLE_STATUS." . $bill->Status) }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="w-10/12 mx-auto text-sm font-medium text-gray-900 mb-8">
            <div class="mb-4">
                <x-button id="toggleFormBtn" class="justify-center" :variation="__('rounded')">
                    <p><span>Ocultar</span>&nbsp;formulario</p>
                </x-button>
            </div>
            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                @include('pages.bill-payable-groups.components.create-payment-form')
            </div>
        </div>

        @if ($errors->count() > 0 && !$errors->first('group_tasa'))
            <div class="w-10/12 p-4 mx-auto text-gray-900 mb-8 bg-white rounded-lg border border-gray-200 shadow-sm">
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

        <div class="w-10/12 mx-auto text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 mb-8">
            <table class="table table-bordered table-hover mx-auto text-center p-0">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($payment_bs_table_cols as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @if ($group_payments_bs->count() > 0)

                        @foreach($group_payments_bs as $bill_payment)
                            <tr>
                                <td class="text-center p-0">{{ $bill_payment->Date }}</td>
                                <td class="text-center p-0">{{ $bill_payment->BankName }}</td>
                                <td class="text-center p-0">{{ $bill_payment->RefNumber }}</td>
                                <td class="text-center p-0">{{ $bill_payment->Tasa }}</td>
                                <td class="text-center p-0">{{ $bill_payment->Amount }}</td>
                                <td class="text-center p-0">{{ $bill_payment->DollarAmount }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="{{ count($payment_bs_table_cols)}}">No hay elementos</td>
                        </tr>   
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <h3 class="h3">Pagos en Dolares</h3>
        </div>

        <div class="w-10/12 mx-auto text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 mb-40">
            <table class="table table-bordered table-hover mx-auto text-center p-0">
                <thead class="bg-blue-300">
                    <tr>
                        @foreach ($payment_dollar_table_cols as $colum)
                            <th scope="col text-center align-middle">{{ $colum }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="billPayableScheduleTBody">
                    @if ($group_payments_dollar->count() > 0)
                        @foreach($group_payments_dollar as $bill_payment)
                            <tr>
                                <td class="text-center p-0">{{ $bill_payment->Date }}</td>
                                <td class="text-center p-0">{{ $bill_payment->PaymentMethod }}</td>
                                <td class="text-center p-0">{{ $bill_payment->RetirementDate }}</td>
                                <td class="text-center p-0">{{ $bill_payment->Amount }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="{{ count($payment_dollar_table_cols)}}">No hay elementos</td>
                        </tr>    
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection