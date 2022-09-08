import { decimalInputs } from '_utilities/decimalInput';

import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

const BillPayablePaymentFormViewPrototype = {
    init(formContainer){
        
        if (!formContainer){
            return false;
        }

        this.formContainer = formContainer;

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

        this.amount.addEventListener('keyup', this.handleKeyupEventAmountWrapper())

        this.localCurrencyContainer.addEventListener('keyup', this.handleKeyupEventLocalCurrencyWrapper())

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
    handleKeyupEventLocalCurrencyWrapper(){
        return (event) => {
            let key = event.key || event.keyCode;
            this.presenter.onKeyupEventLocalCurrency(event.target, event.target.value, key);
        }
    },
    handleKeyupEventAmountWrapper(){
        return (event) => {
            event.preventDefault()
            let key = event.key || event.keyCode;

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
        this.setTasa(tasa)
        this.setTasaInput(tasa)
    },
    showCalculatedDollarAmount(dollarAmount){
        this.dollarAmount.value = dollarAmount
    },
    getTasaInput(){
        return this.tasaInput.value
    },
    getDollarAmount(){
        return this.dollarAmount.value
    },
    setTasa(value){
        this.tasa.value = value
    },
    setTasaInput(value){
        this.tasaInput.value = value
    },
    setBsAmount(value){
        this.amount.value = value
    },
    attachFormSubmissionHandler(cb){
        this.formContainer.addEventListener('submit', cb)
    },
    disableAllFormInputs(){
        this.formContainer.querySelectorAll('input').forEach(el => {
            el.setAttribute('disabled', true)
        })

        this.formContainer.querySelector('select').setAttribute('disabled', true)
    }
}

const BillPayablePaymentFormView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

BillPayablePaymentFormView.prototype = BillPayablePaymentFormViewPrototype;
BillPayablePaymentFormView.prototype.constructor = BillPayablePaymentFormView;

export default BillPayablePaymentFormView;