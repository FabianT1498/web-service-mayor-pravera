import { decimalInputs } from "_utilities/decimalInput";
import { CURRENCIES, SIGN as CURRENCIES_MAP_SIGN } from '_constants/currencies'
import { ERROR, SUCCESS } from '_constants/message-status'
 

const DollarExchangeModalViewPrototype = {
    init(container){
        this.container = container;
        const dollarExchangeButton = this.container.querySelector('#store_dollar_exchange_btn')
        const dollarExchangeInput = this.container.querySelector('#dollar-exchange-bs-input');
        decimalInputs[CURRENCIES.BOLIVAR].mask(dollarExchangeInput)
        dollarExchangeButton.addEventListener('click', this.clickEventHandlerWrapper(this.presenter, dollarExchangeInput))
    },
    clickEventHandlerWrapper(presenter, dollarExchangeInput){
        return (event) => {
            presenter.clickOnStoreDollarExchangeVal(dollarExchangeInput.value);
        }
    },
    showLoading(){
        const postBtn = this.container.querySelector('#store_dollar_exchange_btn');
        postBtn.disabled = true;
        postBtn.innerHTML = `
            <svg role="status" class="inline mr-3 w-4 h-4 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
            </svg>
            Guardando...
        `
    },
    hideLoading(){
        const postBtn = this.container.querySelector('#store_dollar_exchange_btn');
        postBtn.disabled = false;
        postBtn.innerHTML = 'Guardar'
    },
    showMessage(message, type = ERROR){
        let messageEl = this.container.querySelector('#dollar-exchange-message')
        messageEl.classList.remove('hidden')
        
        if (type === ERROR){
            messageEl.classList.add('error')
            messageEl.classList.remove('success')
        } else {
            messageEl.classList.add('success')
            messageEl.classList.remove('error')
        }

        messageEl.innerText = message;
    },
    blockCloseBtn(){
        let closeBtn = this.container.querySelector('button[data-modal-toggle="dollar-exchange-modal"]')
        closeBtn.classList.remove('hover:text-gray-900');
        closeBtn.disabled = true;
    },
    unlockCloseBtn(){
        let closeBtn = this.container.querySelector('button[data-modal-toggle="dollar-exchange-modal"]')
        closeBtn.classList.add('hover:text-gray-900');
        closeBtn.disabled = false;
    },
    hideMessage(){
        let message = this.container.querySelector('#dollar-exchange-message')
        message.classList.add('hidden')
        message.classList.remove('error', 'success')
        message.innerText = '';
    },
    showModal(){
        toggleModal(this.container.getAttribute('id'), true);
    },
    hideModal(){
        toggleModal(this.container.getAttribute('id'), false);
    },
    updateDollarData(dollarExchange){
        this.container.querySelector('#dollar_exchange_value_bs').innerText = `${dollarExchange.value} ${CURRENCIES_MAP_SIGN[CURRENCIES.BOLIVAR]}`
        this.container.querySelector('#dollar_exchange_date_bs').innerText = dollarExchange.createdAt
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
