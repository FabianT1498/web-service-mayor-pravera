const CashRegisterDataPresenterPrototype = {
	changeOnView({ target }) {

		const id = target.getAttribute('id');

		if(id === 'cash_register_worker_exist_check'){
			target.value = target.value === "0" ? "1" : "0"
			this.view.toggleNewWorkerContainer()
		}
   	},
   	setView(view){
		this.view = view;
	},
}

const CashRegisterDataPresenter = function (){
    this.view = null;

}

CashRegisterDataPresenter.prototype = CashRegisterDataPresenterPrototype;
CashRegisterDataPresenter.prototype.constructor = CashRegisterDataPresenter;

export default CashRegisterDataPresenter;