import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

import { decimalInputs } from '_utilities/decimalInput';

import BillPayablePaymentFormView from '_views/BillPayablePaymentFormView'

export default {
    DOMElements: {
        amount: document.querySelector('#amount'),
        tasa: document.querySelector('#tasa'),
        date: document.querySelector('#date'),
        isDollar: document.querySelector('#isDollar'),
    },
    initEventListener(){

        // Initialize date range picker
        Object.assign(Datepicker.locales, es);
      
        let today = this.DOMElements.date.value;

        new Datepicker(this.DOMElements.date, {
            format: 'dd-mm-yyyy',
            language: 'es',
            maxDate: today
        });

        this.DOMElements.isDollar.addEventListener('click', this.handleCheck);

        // decimalInputs['bs'].mask(this.DOMElements.tasa)
        decimalInputs['bs'].mask(this.DOMElements.amount)
    },
    init(){
        this.initEventListener()

        let billPayablePaymentFormContainer = document.querySelector('#billPaymentForm')
        this.billPayablePaymentFormView = new BillPayablePaymentFormView();
        this.billPayablePaymentFormView.init(billPayablePaymentFormContainer)
    }
}
