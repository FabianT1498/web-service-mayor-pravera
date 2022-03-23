import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import { getCashRegisterUsersWithoutRecords } from '_services/cash-register';

const CashRegisterDataPresenterPrototype = {
	changeOnView({ target }) {

		const id = target.getAttribute('id');

		if(id === 'cash_register_worker_exist_check'){
			target.value = target.value === "0" ? "1" : "0"
			this.view.toggleNewWorkerContainer()
		}
   	},
	changeDateOnView({ date }){

		let newDate = Datepicker.formatDate(date, 'yyyy-mm-dd')
		this.getUsersWithoutRecord(newDate)
		
	},
	getUsersWithoutRecord(date){
        this.view.showLoading()
        this.view.hideCashRegisterUsersNoAvailable();
         
		getCashRegisterUsersWithoutRecords(date)
            .then(res => {
                this.view.hideLoading()
                if ([201, 200].includes(res.status)){

                    let data = res.data.data;

					// If there's a stored date on component, then the user is 
					// editing a cash register
					if (this.date && this.cashRegisterUser 
							&& this.date === date){
						data.unshift({key: this.cashRegisterUser, value: this.cashRegisterUser})
					}

					if (data.length === 0){
                        this.view.showCashRegisterUsersNoAvailable();
                        this.view.setCashRegisterUsersElements([]);
                    } else {
                        this.view.setCashRegisterUsersElements(data);
                    }                 
                }
            })
            .catch(err => {
                console.log(err);
            })
	},
   	setView(view){
		this.view = view;
	},
}

const CashRegisterDataPresenter = function (date = null, cashRegisterUser = null){
    this.view = null;
	this.date = date ? date.split('-').reverse().join('-') : null; // Date formatted to Y-m-d
	this.cashRegisterUser = cashRegisterUser;
}

CashRegisterDataPresenter.prototype = CashRegisterDataPresenterPrototype;
CashRegisterDataPresenter.prototype.constructor = CashRegisterDataPresenter;

export default CashRegisterDataPresenter;