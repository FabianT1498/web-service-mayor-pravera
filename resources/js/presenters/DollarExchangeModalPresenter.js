import { postDollarExchange} from '_services/dollar-exchange';
import { boundStoreDollarExchange } from '_store/action'

import { getDollarExchange } from '_services/dollar-exchange';

import toastr  from 'toastr';

const DollarExchangeModalPresenterPrototype = {
    clickOnStoreDollarExchangeVal(bsExchange){
        this.storeNewDollarExchangeValue(bsExchange)
    },
    storeNewDollarExchangeValue: function(value){
        if (parseFloat(value) <= 0){
            this.view.showErrorMessage('La tasa del dolar debe ser mayor a cero')
        }

        postDollarExchange({'bs_exchange': value})
            .then(res => {
                if ([201, 200].includes(res.status)){

                    let data = res.data.data;
                    let dollarExchange = {
                        value: data.bs_exchange,
                        createdAt: data.created_at
                    }
    
                    // Show modal to succesfully value store
                    const message = 'La tasa del dolar ha sido cambiada con exito'
                    toastr.success(message);
                    toggleModal('dollar-exchange-modal', false);
                    
                    // Change value in global state
                    boundStoreDollarExchange(dollarExchange)

                    if (this.closeBtnDisabled){
                        this.view.toggleCloseButtonState();
                        this.closeBtnDisabled = false
                    }
                }
            })
            .catch(err => {
                console.log(err)
                toastr.error('No se pudo guardar el valor de la tasa del dolar');
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
                    this.view.showErrorMessage('Por favor ingresa una tasa para continuar')
                    this.view.toggleCloseButtonState();
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
}

DollarExchangeModalPresenter.prototype = DollarExchangeModalPresenterPrototype
DollarExchangeModalPresenter.prototype.constructor = DollarExchangeModalPresenter;

export default DollarExchangeModalPresenter;