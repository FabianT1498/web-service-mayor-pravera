import { decimalInputs } from "_utilities/decimalInput";
import { CURRENCIES } from '_constants/currencies'
 

const DollarExchangeModalViewPrototype = {
    init(container){
        const dollarExchangeButton = document.querySelector('#store_dollar_exchange_btn')
        const dollarExchangeInput = document.getElementById('dollar-exchange-bs-input');
        decimalInputs[CURRENCIES.BOLIVAR].mask(dollarExchangeInput)
        dollarExchangeButton.addEventListener('click', this.clickEventHandlerWrapper(this.presenter, dollarExchangeInput))
        this.container = container;
    },
    clickEventHandlerWrapper(presenter, dollarExchangeInput){
        return (event) => {
            presenter.clickOnStoreDollarExchangeVal(dollarExchangeInput.value);
        }
    },
    showErrorMessage(message){
        let alertMessage = this.container.querySelector('#dollar-exchange-message')
        alertMessage.classList.toggle('hidden')
        alertMessage.innerText = message;
    },
    toggleCloseButtonState(){
        let closeBtn = this.container.querySelector('button[data-modal-toggle="dollar-exchange-modal"]')
        closeBtn.classList.toggle('hover:text-gray-900');
        closeBtn.disabled = !closeBtn.disabled;
    }
}

function DollarExchangeModalView(presenter){
    this.presenter = presenter;
    this.presenter.setView(this)
    this.presenter.init();
}

DollarExchangeModalView.prototype = DollarExchangeModalViewPrototype;
DollarExchangeModalView.prototype.constructor = DollarExchangeModalView;

export default DollarExchangeModalView
