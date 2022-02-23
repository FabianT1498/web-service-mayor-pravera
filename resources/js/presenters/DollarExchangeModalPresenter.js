import { postDollarExchange} from '_services/dollar-exchange';
import { boundStoreDollarExchange } from '_store/action'

import toastr  from 'toastr';

const DollarExchangeModalPresenterPrototype = {
    clickOnStoreDollarExchangeVal(bsExchange){
        this.storeNewDollarExchangeValue(bsExchange)
    },
    storeNewDollarExchangeValue: function(value){
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
                }
            })
            .catch(err => {
                console.log(err)
                toastr.error('No se pudo guardar el valor de la tasa del dolar');
            })
    },
    setView(view){
        this.view = view;
    }
}

function DollarExchangeModalPresenter(){
    this.view = null
}

DollarExchangeModalPresenter.prototype = DollarExchangeModalPresenterPrototype
DollarExchangeModalPresenter.prototype.constructor = DollarExchangeModalPresenter;

export default DollarExchangeModalPresenter;