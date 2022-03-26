import { postDollarExchange} from '_services/dollar-exchange';
import { boundStoreDollarExchange } from '_store/action'

import { getDollarExchange } from '_services/dollar-exchange';

import { ERROR, SUCCESS } from '_constants/message-status'

import { formatAmount } from '_utilities/mathUtilities'


const DollarExchangeModalPresenterPrototype = {
    clickOnStoreDollarExchangeVal(bsExchange){
        this.storeNewDollarExchangeValue(bsExchange)
    },
    storeNewDollarExchangeValue: function(value){
        let amountConverted = formatAmount(value)
        this.view.hideMessage();

        if (amountConverted <= 0){
            this.message = 'La tasa del dolar debe ser mayor a cero'
            this.view.showMessage(this.message, ERROR)
            return;
        }

        this.view.showLoading();

        postDollarExchange({'bs_exchange': value})
            .then(res => {
                if ([201, 200].includes(res.status)){

                    let data = res.data.data;
                    let dollarExchange = {
                        value: data.bs_exchange,
                        createdAt: data.created_at
                    }
    
                    // Show modal to succesfully value store
                    this.message = 'La tasa del dolar ha sido cambiada con exito'
                    this.view.showMessage(this.message, SUCCESS)
                    
                    // Change value in global state
                    boundStoreDollarExchange(dollarExchange)
                    this.view.hideLoading();

                    if (this.closeBtnDisabled){
                        this.view.unlockCloseBtn();
                        this.closeBtnDisabled = false
                    }

                    this.view.updateDollarData(dollarExchange)                    
                }
            })
            .catch(err => {
                console.log(err);
                // this.message = 'No se pudo guardar el valor de la tasa'
                // this.view.showMessage(this.message, ERROR)
            })
    },
    setView(view){
        this.view = view;
    },
    init(){
        if (!this.view){
            return;
        }

        getDollarExchange()
            .then(res => {
                let data = res.data.data;
            
                if (!data && this.view){
                    this.view.showModal()
                    this.message = 'Por favor ingresa una tasa para continuar'
                    this.view.showMessage(this.message, ERROR)
                    this.view.blockCloseBtn();
                    this.closeBtnDisabled = true
                    return;
                }

                let dollarExchange = {
                    value: data.bs_exchange,
                    createdAt: data.created_at
                }
                boundStoreDollarExchange(dollarExchange)
            })
        .catch(err => {
            console.log(err)
        })
    }
}

function DollarExchangeModalPresenter(){
    this.view = null
    this.closeBtnDisabled = false;
    this.message = '';
}

DollarExchangeModalPresenter.prototype = DollarExchangeModalPresenterPrototype
DollarExchangeModalPresenter.prototype.constructor = DollarExchangeModalPresenter;

export default DollarExchangeModalPresenter;