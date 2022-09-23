<form 
    id="billPaymentForm" 
    action="{{ route('bill_payable_groups.store-payment') }}" 
    method="POST" 
    class="w-full"
>
    @csrf
    <input type="hidden" value="{{$group->ID}}" name="group_id">
  
    <div class="flex mb-4">
        <h4 class="h4">Datos de pago</h4>
    </div>
    
    <div class="flex items-center w-full flex-wrap mb-4">
        
        <div class="flex items-center">
            <label class="basis-1/3" for="paymentCurrency">Divisa:</label>
            <x-select-input 
                :defaultOptTitle="__('Seleccione la divisa')"
                id="paymentCurrency"
                :value="$payment_currency"
                :name="__('payment_currency')" 
                :options="$payment_currencies"
                class="w-full relative ml-4"
            />
        </div>

        <div class="flex items-center basis-[30%] ml-8">
            <label class="basis-1/3" for="amount">Monto:</label>
            <input 
                class="{{ 'w-full ml-4 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                data-bill="amount"
                type="text" 
                value="{{ old('amount') ? old('amount') : 0.00 }}"
                id="amount" 
                name="amount"
                required
                autocomplete="off"
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
                :name="__('bank_name')"
                :value="old('bank_name') ? old('bank_name') : ''"
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
                value="{{ old('ref_number') ? old('ref_number') : '' }}"
                name="ref_number"
                required
                autocomplete="off"
            >
        </div>

        <div class="flex items-center basis-[30%] mb-2">
            <label class="basis-1/3" for="tasa">Tasa:</label>
            <input type="hidden"  value="{{ old('tasa') ? old('tasa') : 0.00 }}" name="tasa" id="tasa" required>
            <input 
                class="{{ 'border w-full ml-4  border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                data-bill="tasa"
                type="text" 
                id="tasaInput" 
                value="{{ old('tasa') ? old('tasa') : 0.00 }}"
                required
            >
        </div>
        
        <div class="flex items-center basis-[30%] mb-2">
            <label class="basis-1/3" for="amount">Monto ($):</label>
            <input 
                class="{{ 'ml-4 w-full border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700' . 
                    ' dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 bg-gray-50'
                }}"
                data-bill="dollarAmount"
                type="text" 
                id="dollarAmount" 
                value="{{ old('dollarAmount') ? old('dollarAmount') : 0.00 }}"
                name="dollarAmount"
                required
            >
        </div>
    </div>

    <div id="foreignCurrencyContainer" class="flex items-center w-full flex-wrap mb-4 hidden">

        <div class="flex items-center basis-[30%]">
            <label class="basis-1/3" for="foreign_currency_payment_method">Modalidad de pago:</label>
            <x-select-input
                :options="$foreign_currency_payment_methods"
                :defaultOptTitle="__('Seleccione una modalidad de pago')"
                id="paymentMethod"
                :name="__('foreign_currency_payment_method')"
                :value="old('foreign_currency_payment_method') ? old('foreign_currency_payment_method') : ''"
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
                onkeydown="return false"
            />
        </div>        
    </div>
                
    <x-button 
        class="justify-center" 
        :variation="__('rounded')" 
        :disabled="$last_tasa->Tasa === 0.00 || config('constants.BILL_PAYABLE_STATUS.' . $group->Estatus) === config('constants.BILL_PAYABLE_STATUS.PAID')"
        :dataTooltipTarget="__('tasaNotDefinedSuggestion')"
        :dataTooltipPlacement="__('right')"
    >
        <span>Guardar</span>
    </x-button>

    @if ($last_tasa->Tasa === 0.00 || config("constants.BILL_PAYABLE_STATUS." . $group->Estatus) === config("constants.BILL_PAYABLE_STATUS.PAID"))
        <div 
            id="tasaNotDefinedSuggestion"
            role="tooltip" 
            class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700"
        >
            {{ $last_tasa->Tasa === 0.00 
                ? 'No puede generar un pago hasta que la factura tenga definida una tasa.'
                : (config("constants.BILL_PAYABLE_STATUS." . $group->Estatus) === config("constants.BILL_PAYABLE_STATUS.PAID") 
                    ? 'Esta factura ya ha sido pagada, no puede generar mas pagos.'
                    : '')
            }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    @endif
</form> 