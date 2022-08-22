import BillPayablePaymentFormView from '_views/BillPayablePaymentFormView'
import BillPayablePaymentFormPresenter from '_presenters/BillPayablePaymentFormPresenter'

export default {
    init(){

        let billPayablePaymentFormContainer = document.querySelector('#billPaymentForm')

        let nroDoc = document.querySelector('#nroDoc')
        let codProv = document.querySelector('#codProv')

        this.billPayablePaymentFormPresenter = new BillPayablePaymentFormPresenter(nroDoc ? nroDoc.value : '', codProv ? codProv.value : '')
        this.billPayablePaymentFormView = new BillPayablePaymentFormView(this.billPayablePaymentFormPresenter);
        this.billPayablePaymentFormView.init(billPayablePaymentFormContainer)
    }
}
