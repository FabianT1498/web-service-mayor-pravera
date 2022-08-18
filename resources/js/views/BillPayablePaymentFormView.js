const BillPayablePaymentFormViewPrototype = {
    init(formContainer){
        
        if (!formContainer){
            return false;
        }

        let id = formContainer.getAttribute('id')

        this.localCurrencyContainer = formContainer.querySelector('#localCurrencyContainer')
        this.foreignCurrencyContainer = formContainer.querySelector('#foreignCurrencyContainer')

        this.isDollar = formContainer.querySelector('#isDollar')
        this.isDollar.addEventListener('click', this.handleClickIsDollarWrapper());
    },
    handleClickIsDollarWrapper(){
        return event => {
            this.isDollar.value = event.target.value === '0' ? '1' : '0';

            this.toggleForeignCurrencyForm();
            this.toggleLocalCurrencyForm();
            // if (this.isDollar.value === '1'){
            //     this.hideLocalCurrencyForm()
            //     this.showForeignCurrencyForm()
            // } else {
            //     this.showLocalCurrencyForm()
            //     this.hideForeignCurrencyForm()
            // }
        }
    },
    toggleLocalCurrencyForm(){
        this.localCurrencyContainer.classList.toggle('hidden')
    },
    toggleForeignCurrencyForm(){
        this.foreignCurrencyContainer.classList.toggle('hidden')
    },
    // showForeignCurrencyForm(){
    //     this.foreignCurrencyContainer.classList.add('hidden')
    // },
    // hideForeignCurrencyForm(){
    //     this.foreignCurrencyContainer.classList.remove('hidden')
    // }
}

const BillPayablePaymentFormView = function (){
    // this.presenter = presenter;
    // this.presenter.setView(this);
}

BillPayablePaymentFormView.prototype = BillPayablePaymentFormViewPrototype;
BillPayablePaymentFormView.prototype.constructor = BillPayablePaymentFormView;

export default BillPayablePaymentFormView;