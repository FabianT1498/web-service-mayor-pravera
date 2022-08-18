<form id="billPaymentForm" action="{{ route('bill_payable.store-payment') }}" method="POST" class="w-full">
    @csrf
    <input type="hidden" value="{{$bill->NumeroD}}" name="nro_doc">
    <input type="hidden" value="{{$bill->CodProv}}" name="cod_prov">
    
    <div class="flex items-center w-full flex-wrap mb-4">
        
        <div class="flex items-center">
            <label for="isDollar">Es dolar:</label>
            <input
                class="form-checkbox w-4 h-4 ml-2 text-blue-600 rounded  focus:ring-blue-500 focus:ring-2 border-gray-300"
                type="checkbox"       
                value="{{ old('isDollar') === null ?  '0' : '1' }}"               
                id="isDollar"
                name="isDollar"
                {{old('isDollar') === '1' ? 'checked' : '' }}
            />
        </div>

        <div class="flex items-center basis-[30%] ml-8">
            <label class="basis-1/3" for="amount">Monto:</label>
            <input 
                class="{{ 'w-full ml-4 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                type="text" 
                id="amount" 
                value="{{ old('amount') ? old('amount') : 0.00 }}"
                name="amount"
                required
            >
        </div>
        
        <div class="flex items-center basis-[30%] ml-8">
            <label class="basis-1/3" for="date">Fecha pago:</label>
            <input
                class="{{ 'w-full ml-4 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                type="text"
                value="{{ old('date') ? old('date') : $today_date }}"              
                id="date"
                name="date"
                required
                onkeydown="return false"
            />
        </div>

    </div>

    <div id="localCurrencyContainer" class="flex items-center w-full flex-wrap justify-between mb-4">

        <div class="flex items-center basis-[30%] mb-2">
            <label class="basis-1/3" for="bankSelect">Banco:</label>
            <x-select-input
                :options="$banks"
                :defaultOptTitle="__('Seleccione un banco')"
                id="bankSelect"
                :name="__('bank')"
                :value="old('bank') ? old('bank') : ''"
                required
                class="w-full ml-4 "
            />
        </div>
            
        <div class="flex items-center basis-[30%] mb-2">
            <label class="basis-1/3" for="referenceNumber">Número de referencia:</label>
            <input 
                class="{{ 'w-full ml-4  border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                type="text" 
                id="referenceNumber" 
                value="{{ old('referenceNumber') ? old('referenceNumber') : '' }}"
                name="referenceNumber"
                required
            >
        </div>

        <div class="flex items-center basis-[30%] mb-2">
            <label class="basis-1/3" for="tasa">Tasa:</label>
            <input 
                class="{{ 'border w-full ml-4  border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                type="text" 
                id="tasa" 
                value="{{ old('tasa') ? old('tasa') : 0.00 }}"
                name="tasa"
                required
            >
        </div>
        
        <div class="flex items-center basis-[30%] mb-2">
            <label class="basis-1/3" for="amount">Monto ($):</label>
            <input 
                class="{{ 'ml-4 w-full border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                type="text" 
                id="dollarAmount" 
                value="{{ old('dollarAmount') ? old('dollarAmount') : 0.00 }}"
                name="amount"
                required
            >
        </div>
    </div>

    <div id="foreignCurrencyContainer" class="flex items-center w-full flex-wrap mb-4 hidden">

        <div class="flex items-center basis-[30%]">
            <label class="basis-1/3" for="bankSelect">Modalidad de pago:</label>
            <x-select-input
                :defaultOptTitle="__('Seleccione una modalidad de pago')"
                id="paymentMethod"
                :name="__('payment_method')"
                :value="old('payment_method') ? old('payment_method') : ''"
                required
                class="w-full ml-4 "
            />
        </div>

        <div class="flex items-center basis-[30%] ml-8">
            <label class="basis-1/3" for="retirementDate">Fecha de retiro:</label>
            <input
                class="{{ 'w-full ml-4 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                type="text"
                value="{{ old('retirement_Date') ? old('retirement_Date') : $today_date }}"              
                id="retirementDate"
                name="retirement_date"
                required
                onkeydown="return false"
            />
        </div>        
    </div>

    <x-button class="justify-center" :variation="__('rounded')">
        <span>Guardar</span>
    </x-button>
</form> 