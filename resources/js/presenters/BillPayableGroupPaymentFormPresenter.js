import { getBillPayableGroupByID } from '_services/bill-payable';
import { formatAmount, roundNumber } from '_utilities/mathUtilities'

import { BILL_STATUSES } from '_constants/billStatuses';


const BillPayableGroupPaymentFormPresenterPrototype = {
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
  
        if (key === 8 || key === 'Backspace' || isFinite(key)){
                
            let formattedValue = formatAmount(value)

            let tasaRel = roundNumber(formattedValue / this.groupData.group.MontoTotal)
            let dollarAmount = roundNumber(formattedValue / tasaRel)
            
            this.view.showCalculatedRate(tasaRel)
            this.view.showCalculatedDollarAmount(dollarAmount)
        }
    },
	setView(view){
		this.view = view;
	},
    formSubmissionHandlerWrapper(tasa, groupStatus){
        return (event) => {

            // Si la factura esta expresada en Bs y la tasa referencial es cero,
            // o la factura ha sido pagada en su totalidad, entonces prevenir generacion de pagos
            if (roundNumber(tasa) === 0 || BILL_STATUSES[groupStatus] === BILL_STATUSES.PAID){ 
                event.preventDefault();
                return false;
            }
        }
    }
}

const BillPayableGroupPaymentFormPresenter = function (groupID){
    this.view = null;

    if (groupID !== ''){
        getBillPayableGroupByID(groupID).then(res => {
            this.groupData = res.data[0]
            this.view.attachFormSubmissionHandler(this.formSubmissionHandlerWrapper(this.groupData.last_tasa.Tasa, this.groupData.group.Status))
            
            if ((roundNumber(this.groupData.last_tasa.Tasa) === 0) || BILL_STATUSES[this.groupData.group.Status] === BILL_STATUSES.PAID){
                this.view.disableAllFormInputs();
            }
        }).catch(err => {
            console.log(err)
        })
    }
}

BillPayableGroupPaymentFormPresenter.prototype = BillPayableGroupPaymentFormPresenterPrototype;
BillPayableGroupPaymentFormPresenter.prototype.constructor = BillPayableGroupPaymentFormPresenter;

export default BillPayableGroupPaymentFormPresenter;
