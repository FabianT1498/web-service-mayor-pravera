@extends('layouts.app')

@section('js')
    <script src="{{ asset('js/bills_payable_show.js') }}" defer></script>
@endsection

@section('main')
    <div class="mx-auto w-11/12">
        <div class="mb-8">
            <h2 class="h2">Pagos de la factura</h2>
        </div>

        <div class="w-4/6 mx-auto text-sm font-medium text-gray-900 mb-8 ">
            <div class="mb-4">
                <x-button id="toggleFormBtn" class="justify-center" :variation="__('rounded')">
                    <p><span>Ocultar</span>&nbsp;formulario</p>
                </x-button>
            </div>
            <div class="p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                <form action="{{ route('bill_payable.store-payment') }}" method="POST" class="w-full">
                    @csrf
                    <input type="hidden" value="{{$bill->NumeroD}}" name="nro_doc">
                    <input type="hidden" value="{{$bill->CodProv}}" name="cod_prov">
                    <div class="grid grid-cols-[10%_20%_10%_20%_10%_20%] items-center justify-center gap-x-3 gap-y-3">
                        <label for="bankSelect">Banco:</label>
                        <x-select-input
                            :options="$banks"
                            :defaultOptTitle="__('Seleccione un banco')"
                            id="bankSelect"
                            :name="__('bank')"
                            :value="old('bank') ? old('bank') : ''"
                            required
                        />
                        <label for="amount">Monto:</label>
                        <input 
                            class="{{ 'border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                            }}"
                            type="text" 
                            id="amount" 
                            value="{{ old('amount') ? old('amount') : 0.00 }}"
                            name="amount"
                            required
                        >
                        <label for="referenceNumber">Número de referencia:</label>
                        <input 
                            class="{{ 'border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                            }}"
                            type="text" 
                            id="referenceNumber" 
                            value="{{ old('referenceNumber') ? old('referenceNumber') : '' }}"
                            name="referenceNumber"
                            required
                        >
                        
                        <label for="tasa">Tasa:</label>
                        <input 
                            class="{{ 'border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                            }}"
                            type="text" 
                            id="tasa" 
                            value="{{ old('tasa') ? old('tasa') : 0.00 }}"
                            name="tasa"
                            required
                        >
                        <label for="date">Fecha pago:</label>
                        <input
                            class="{{ 'border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                                ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                            }}"
                            type="text"
                            value="{{ old('date') ? old('date') : $today_date }}"              
                            id="date"
                            name="date"
                            required
                            onkeydown="return false"
                        />
                        <label for="isDollar">Es dolar:</label>
                        <input
                            class="form-checkbox w-4 h-4 text-blue-600 rounded  focus:ring-blue-500 focus:ring-2 border-gray-300"
                            type="checkbox"       
                            value="{{ old('isDollar') === null ?  '0' : '1' }}"               
                            id="isDollar"
                            name="isDollar"
                            {{old('isDollar') === '1' ? 'checked' : '' }}
                        />
                        <span>&nbsp;</span>
                    </div>
                    <x-button class="justify-center" :variation="__('rounded')">
                        <span>Guardar</span>
                    </x-button>
                </form> 
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
                    @foreach($bill_payments as $bill_payment)
                        <tr>
                            <td class="text-center p-0">{{ date('d-m-Y', strtotime($bill_payment->Date)) }}</td>
                            <td class="text-center p-0">{{ $bill_payment->BankName }}</td>
                            <td class="text-center p-0">{{ $bill_payment->RefNumber }}</td>
                            <td class="text-center p-0">{{ $bill_payment->esDolar ? "Si" : "No" }}</td>
                            <td class="text-center p-0">{{ $bill_payment->Tasa }}</td>
                            <td class="text-center p-0">{{ $bill_payment->Amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection