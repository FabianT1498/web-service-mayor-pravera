import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

import { decimalInputs } from '_utilities/decimalInput';

export default {
    DOMElements: {
        amount: document.querySelector('#amount'),
        tasa: document.querySelector('#tasa'),
        date: document.querySelector('#date'),
        isDollar: document.querySelector('#isDollar'),
    },
    handleCheck: function(event){
        event.target.value = event.target.value === '0' ? '1' : '0';
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

        decimalInputs['bs'].mask(this.DOMElements.tasa)
        decimalInputs['bs'].mask(this.DOMElements.amount)
    },
    init(){
        this.initEventListener()
    }
}
