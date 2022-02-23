import { decimalInputs } from "_utilities/decimalInput";
import { CURRENCIES } from '_constants/currencies'
 

const DollarExchangeModalViewPrototype = {
    init(){
        const dollarExchangeButton = document.querySelector('#store_dollar_exchange_btn')
        const dollarExchangeInput = document.getElementById('dollar-exchange-bs-input');
        decimalInputs[CURRENCIES.BOLIVAR].mask(dollarExchangeInput)
        dollarExchangeButton.addEventListener('click', this.clickEventHandlerWrapper(this.presenter, dollarExchangeInput))
    },
    clickEventHandlerWrapper(presenter, dollarExchangeInput){
        return (event) => {
            presenter.clickOnStoreDollarExchangeVal(dollarExchangeInput.value);
        }
    },
}

function DollarExchangeModalView(presenter){
    this.presenter = presenter;
    this.presenter.setView(this)
}

DollarExchangeModalView.prototype = DollarExchangeModalViewPrototype;
DollarExchangeModalView.prototype.constructor = DollarExchangeModalView;

export default DollarExchangeModalView
