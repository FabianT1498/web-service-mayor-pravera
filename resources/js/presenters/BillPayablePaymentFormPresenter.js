import { getBillPayable } from '_services/bill-payable';
import { formatAmount, roundNumber } from '_utilities/mathUtilities'
const BillPayablePaymentFormPresenterPrototype = {
	onKeyupEventAmount(value, key){
  
        if (this.bill.TipoCom === 'NE' && this.bill.esDolar === 1 
                && (key === 8 || key === 'Backspace' || isFinite(key))){
                
            let formattedValue = formatAmount(value)

            let tasaRel = roundNumber(formattedValue / this.bill.MontoTotal)
            let dollarAmount = roundNumber(formattedValue / tasaRel)
            
            this.view.showCalculatedRate(tasaRel)
            this.view.showCalculatedDollarAmount(dollarAmount)
        }

    },
	setView(view){
		this.view = view;
	},
}

const BillPayablePaymentFormPresenter = function (numeroD, codProv){
    this.view = null;

    if (numeroD !== '' && codProv !== ''){
        getBillPayable({numeroD, codProv}).then(res => {
            this.bill = res.data[0]
            console.log(this.bill)
        }).catch(err => {
            console.log(err)
        })
    }
}

BillPayablePaymentFormPresenter.prototype = BillPayablePaymentFormPresenterPrototype;
BillPayablePaymentFormPresenter.prototype.constructor = BillPayablePaymentFormPresenter;

export default BillPayablePaymentFormPresenter;
