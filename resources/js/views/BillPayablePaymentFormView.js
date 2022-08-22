import { decimalInputs } from '_utilities/decimalInput';

import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

const BillPayablePaymentFormViewPrototype = {
    init(formContainer){
        
        if (!formContainer){
            return false;
        }

        let id = formContainer.getAttribute('id')

        this.localCurrencyContainer = formContainer.querySelector('#localCurrencyContainer')
        this.foreignCurrencyContainer = formContainer.querySelector('#foreignCurrencyContainer')

        this.isDollar = formContainer.querySelector('#is_dollar')
        this.amount = formContainer.querySelector('#amount')
        this.date = formContainer.querySelector('#date')

        this.bankSelect = formContainer.querySelector('#bankSelect')
        this.referenceNumber = formContainer.querySelector('#referenceNumber')
        this.tasa = formContainer.querySelector('#tasa')
        this.tasaInput = formContainer.querySelector('#tasaInput')
        this.dollarAmount = formContainer.querySelector('#dollarAmount')

        this.paymentMethod = formContainer.querySelector('#paymentMethod')
        this.retirementDate = formContainer.querySelector('#retirementDate')

        this.initEventListener()
    },
    initEventListener(){
        
        this.isDollar.addEventListener('click', this.handleClickIsDollarWrapper());
        this.amount.addEventListener('keyup', this.handleKeyupWrapper())

        Object.assign(Datepicker.locales, es);
      
        let today = this.date.value;

        new Datepicker(this.date, {
            format: 'dd-mm-yyyy',
            language: 'es',
            maxDate: today
        });

        new Datepicker(this.date, {
            format: 'dd-mm-yyyy',
            language: 'es',
            maxDate: today
        });

        new Datepicker(this.retirementDate, {
            format: 'dd-mm-yyyy',
            language: 'es',
            maxDate: today
        });

        decimalInputs['bs'].mask(this.amount)
        decimalInputs['bs'].mask(this.tasaInput)
        decimalInputs['dollar'].mask(this.dollarAmount)
    },
    handleKeyupWrapper(){
        return (event) => {

            let key = event.key || event.keyCode;
            if (key === 13 || key === 'Enter'){
                event.preventDefault()
            }
            
            this.presenter.onKeyupEventAmount(event.target.value, key);
        }
    },
    handleClickIsDollarWrapper(){
        return event => {
            this.isDollar.value = (event.target.value === '0') ? '1' : '0';

            this.toggleForeignCurrencyForm();
            this.toggleLocalCurrencyForm();
            this.toggleForeignCurrencyRequiredInputs()
            this.toggleLocalCurrencyRequiredInputs()

            this.amount.inputmask.remove()

            if(this.isDollar.value === '0'){
                decimalInputs['bs'].mask(this.amount)
            } else {
                decimalInputs['dollar'].mask(this.amount)
            }
        }
    },
    toggleForeignCurrencyRequiredInputs(){
        this.paymentMethod.toggleAttribute('required')
        this.retirementDate.toggleAttribute('required')
    },
    toggleLocalCurrencyRequiredInputs(){
        this.bankSelect.toggleAttribute('required')
        this.referenceNumber.toggleAttribute('required')
        this.tasa.toggleAttribute('required')
    },
    toggleLocalCurrencyForm(){
        this.localCurrencyContainer.classList.toggle('hidden')
    },
    toggleForeignCurrencyForm(){
        this.foreignCurrencyContainer.classList.toggle('hidden')
    },
    showCalculatedRate(tasa){
        this.tasa.value = tasa
        this.tasaInput.value = tasa
    },
    showCalculatedDollarAmount(dollarAmount){
        this.dollarAmount.value = dollarAmount
    }
}

const BillPayablePaymentFormView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

BillPayablePaymentFormView.prototype = BillPayablePaymentFormViewPrototype;
BillPayablePaymentFormView.prototype.constructor = BillPayablePaymentFormView;

export default BillPayablePaymentFormView;