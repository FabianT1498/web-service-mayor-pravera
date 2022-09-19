import BillPayableGroupPaymentFormView from '_views/BillPayablePaymentFormView'
import BillPayableGroupPaymentFormPresenter from '_presenters/BillPayablePaymentFormPresenter'

import { decimalInputs } from '_utilities/decimalInput';

export default {
    DOMElements: {
        billPayablePaymentForm: document.querySelector('#billPaymentForm'),
        toggleFormBtn: document.querySelector('#toggleFormBtn')
    },
    initEventLister(){
        this.groupTasa = document.querySelector('#groupTasa')
        if (this.groupTasa) {
            decimalInputs['bs'].mask(this.groupTasa)
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

        let groupID = document.querySelector('#group_id')
        
        this.billPayableGroupPaymentFormPresenter = new BillPayableGroupPaymentFormPresenter(groupID ? groupID.value : '')
        this.billPayableGroupPaymentFormView = new BillPayableGroupPaymentFormView(this.billPayableGroupPaymentFormPresenter);
        this.billPayableGroupPaymentFormView.init(this.DOMElements.billPayablePaymentForm)
    }
}
