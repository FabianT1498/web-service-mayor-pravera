@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_show.js') }}" defer></script>
@endsection

@section('main')
    <div class="mx-auto w-11/12">
        <div class="mb-8">
            <h2 class="h2">Pagos de la factura</h2>
        </div>

        <div class="w-4/6 mx-auto p-6 bg-white rounded-lg border border-gray-200 shadow-sm mb-8">
            <div class="flex mb-4">
                <h4 class="h4">Datos de la factura</h4>
            </div>
            <div>
                <form action="{{ route('bill_payable.update-tasa') }}" method="POST" class="w-full">
                    @csrf
                    @method('PUT')
                    <input type="hidden" value="{{$bill->NumeroD}}" name="nro_doc" id="nroDoc">
                    <input type="hidden" value="{{$bill->CodProv}}" name="cod_prov" id="codProv">
                    
                    <div class="flex mb-4">
    
                        <div class="w-1/5">
                            <span class="font-semibold">Monto total: </span>
                            <p>{{ number_format($bill->MontoTotal, 2) . " " . config("constants.CURRENCY_SIGNS." . ($bill->esDolar ? "dollar" : "bolivar")) }}</p>
                        </div>
    
                        <div class="w-1/5 ml-4">
                            <span class="font-semibold">Monto ref. ($): </span>
                            @if(!$bill->esDolar && $bill->Tasa > 0)
                                <p>{{ number_format(($bill->MontoTotal / $bill->Tasa), 2) . " " . config("constants.CURRENCY_SIGNS.dollar") }}</p>
                            @else(!$bill->esDolar && $bill->Tasa === 0)
                                <p>Suministre la tasa de la factura</p>
                            @endif
                        </div>
                        
                        <div class="w-1/5 ml-4">
                            <span class="font-semibold">Monto por pagar: </span>
                            <p>{{ number_format($bill->MontoPagar, 2) . " " . config("constants.CURRENCY_SIGNS." . ($bill->esDolar ? "dollar" : "bolivar")) }}</p>
                        </div>
    
                        <div class="w-1/5 ml-4">
                            <span class="font-semibold">Nro Factura: </span>
                            <p>{{$bill->NumeroD}}</p>
                        </div>

                    </div>
                    <div class="flex justify-between mb-4">
                        <div class="flex justify-between items-center w-1/2">
                            
                            <div>
                                <span class="font-semibold">Proveedor: </span>
                                <p>{{$bill->DescripProv}}</p>
                            </div>
        
                            <div>
                                <span class="font-semibold block mb-2">Tasa: </span>
                                <input 
                                    class="{{ 'border w-full  border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                        ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                                    }}"
                                    type="text"
                                    name="bill_tasa"
                                    id="billTasa" 
                                    value="{{ old('bill_tasa') ? old('bill_tasa') : $bill->Tasa }}"
                                    required
                                >
                            </div>

                        </div>
                       
                        <div class="flex flex-col justify-end">
                            <x-button :variation="__('rounded')">
                                <span>Actualizar tasa</span>
                            </x-button>
                        </div>
                    </div>
                    
                    @if ($errors->first('bill_tasa'))
                        <div>
                            <div class="mb-2 flex">
                                <h4 class="h4 mb-0">Errores</h4>
                            </div>
                            <ul>
                                <li class="list-none error">{{$errors->first('bill_tasa')}}</li>
                            </ul>
                        </div>
                    @endif
                </form>
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

        @if ($errors->count() > 0 && is_null($errors->first('bill_tasa')))
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