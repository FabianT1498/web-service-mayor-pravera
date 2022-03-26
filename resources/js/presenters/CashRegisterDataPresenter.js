import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import { getCashRegisterUsersWithoutRecords, getTotalsToCashRegisterUser } from '_services/cash-register';

const CashRegisterDataPresenterPrototype = {
	changeOnView({ target }) {

		const id = target.getAttribute('id');

		if(id === 'cash_register_worker_exist_check'){
			target.value = target.value === "0" ? "1" : "0"
			this.view.toggleNewWorkerContainer()
		} else if (id === 'cash_register_id'){
			this.selectedCashRegisterUser = target.value;
			this.getTotalsToCashRegisterUserOption(this.selectedDate, this.selectedCashRegisterUser)
		}
   	},
	changeDateOnView({ date }){

		let newDate = Datepicker.formatDate(date, 'yyyy-mm-dd')
		this.selectedDate = newDate
		this.getUsersWithoutRecord(newDate)
		
	},
	getTotalsToCashRegisterUserOption(date, cashRegisterUser){
		this.setTotalAmounts(null)
		getTotalsToCashRegisterUser({date, cashRegisterUser})
			.then(res => {
				if ([201, 200].includes(res.status)){
					let data = res.data.data;
					console.log(data);
					this.setTotalAmounts(data)
				}
			})
			.catch(err => {
				console.log(err);
			})
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
					if (this.defaultDate && this.defaultCashRegisterUser 
							&& this.defaultDate === date){
						data.unshift({key: this.defaultCashRegisterUser, value: this.defaultCashRegisterUser})
					}

					if (data.length === 0){
                        this.view.showCashRegisterUsersNoAvailable();
                    }
                    
					this.view.setCashRegisterUsersElements(data);
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

const CashRegisterDataPresenter = function (setTotalAmounts, date = null, cashRegisterUser = null){
    this.view = null;
	let today = new Date();

	this.defaultDate = date 
		? date.split('-').reverse().join('-') 
		: today.getFullYear()+'-'+(today.getMonth() + 1)+'-'+today.getDate();

	this.defaultCashRegisterUser = cashRegisterUser;

	this.selectedDate = this.defaultDate;
	this.selectedCashRegisterUser = this.defaultCashRegisterUser;
	this.setTotalAmounts = setTotalAmounts;
}

CashRegisterDataPresenter.prototype = CashRegisterDataPresenterPrototype;
CashRegisterDataPresenter.prototype.constructor = CashRegisterDataPresenter;

export default CashRegisterDataPresenter;