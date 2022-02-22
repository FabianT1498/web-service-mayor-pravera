import { createStore } from 'redux';
import { storeDollarExchange } from '_services/dollar-exchange';
import { decimalInputs } from "_utilities/decimalInput";
import { CURRENCIES } from '_constants/currencies'

const DollarExchangeModalView = {
    init(container){
        const dollarExchangeInput = document.getElementById('dollar-exchange-bs-input');
        decimalInputs[CURRENCIES.BOLIVAR].mask(dollarExchangeInput)
        container.addEventListener('click', clickEventHandlerWrapper(this.presenter, dollarExchangeInput))
    },
    clickEventHandlerWrapper(presenter, dollarExchangeInput){
        return (event) => {
            presenter.clickOnModal({
                bsExchange: dollarExchangeInput.value
            });
        }
    },
}