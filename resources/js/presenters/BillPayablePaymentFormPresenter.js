import { getBillPayable } from '_services/bill-payable';
import { formatAmount, roundNumber } from '_utilities/mathUtilities'

import { BILL_STATUSES } from '_constants/billStatuses';


const BillPayablePaymentFormPresenterPrototype = {
    onKeyupEventLocalCurrency(target, value, key){
        if ((key === 8 || key === 'Backspace') || isFinite(key) && target.getAttribute('data-bill')){
            const dataBill = target.getAttribute('data-bill');

            let amountBs = 0;

            let formattedVal = formatAmount(value)

            if (dataBill === 'tasa'){
                this.view.setTasa(formattedVal)
                amountBs = roundNumber(formatAmount(this.view.getDollarAmount()) * formattedVal)
            } else {
                amountBs = roundNumber(formatAmount(this.view.getTasaInput()) * formatAmount(value)) 
            }

            this.view.setBsAmount(amountBs)
        }
    },
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
    formSubmissionHandlerWrapper(tasa, isDollar, billStatus){
        return (event) => {
            // Si la factura esta expresada en Bs y la tasa referencial es cero,
            // o la factura ha sido pagada en su totalidad, entonces prevenir generacion de pagos
            if ((roundNumber(tasa) === 0 && isDollar === 0) || BILL_STATUSES[billStatus] === BILL_STATUSES.PAID){ 
                event.preventDefault();
                return false;
            }
        }
    }
}

const BillPayablePaymentFormPresenter = function (numeroD, codProv){
    this.view = null;

    if (numeroD !== '' && codProv !== ''){
        getBillPayable({numeroD, codProv}).then(res => {
            this.bill = res.data[0]
            this.view.attachFormSubmissionHandler(this.formSubmissionHandlerWrapper(this.bill.Tasa, this.bill.esDolar, this.bill.Status))
            if ((roundNumber(this.bill.Tasa) === 0 && this.bill.esDolar === 0) || BILL_STATUSES[this.bill.Status] === BILL_STATUSES.PAID){
                this.view.disableAllFormInputs();
            }
        }).catch(err => {
            console.log(err)
        })
    }
}

BillPayablePaymentFormPresenter.prototype = BillPayablePaymentFormPresenterPrototype;
BillPayablePaymentFormPresenter.prototype.constructor = BillPayablePaymentFormPresenter;

export default BillPayablePaymentFormPresenter;
