import BillPayablePaymentFormView from '_views/BillPayablePaymentFormView'
import BillPayablePaymentFormPresenter from '_presenters/BillPayablePaymentFormPresenter'

import { decimalInputs } from '_utilities/decimalInput';

export default {
    DOMElements: {
        billPayablePaymentForm: document.querySelector('#billPaymentForm'),
        toggleFormBtn: document.querySelector('#toggleFormBtn')
    },
    initEventLister(){
        this.billTasa = document.querySelector('#billTasa')
        if (this.billTasa) {
            decimalInputs['bs'].mask(this.billTasa)
        }

        this.DOMElements.toggleFormBtn.addEventListener('click', this.toggleFormWrapper())
    },
    toggleFormWrapper(){
        return (event) => {
            const isPresent = this.DOMElements.billPayablePaymentForm.classList.toggle('hidden')
            event.target.innerHTML = (isPresent ? 'Mostrar' : 'Ocultar') + ' formulario'
        }
    },
    init(){
        this.initEventLister()

        let nroDoc = document.querySelector('#nroDoc')
        let codProv = document.querySelector('#codProv')

        this.billPayablePaymentFormPresenter = new BillPayablePaymentFormPresenter(nroDoc ? nroDoc.value : '', codProv ? codProv.value : '')
        this.billPayablePaymentFormView = new BillPayablePaymentFormView(this.billPayablePaymentFormPresenter);
        this.billPayablePaymentFormView.init(this.DOMElements.billPayablePaymentForm)
    }
}
