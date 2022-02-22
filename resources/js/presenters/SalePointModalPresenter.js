import BankCollection from '_app/collections/bankCollection';
import { getAllBanks } from '_services/banks'
import Bank from '_models/Bank'

const SalePointModalPresenterPrototype = {
	clickOnModal({ target }) {
		const button = target.closest('button');

		if(button && button.tagName === 'BUTTON'){
		   const action = button.getAttribute('data-modal')

			if (!action){
				return;
			}

			if (action === 'add'){
				if (this.banks.length === 0){
					return;
				}
					
				let idArr = [];
				let bank = new Bank(this.banks.shift());
	
				if (this.selectedBanks.getLength() > 0){
					idArr = this.selectedBanks.getAll().map((el) => el.id);	
					bank = this.selectedBanks.pushElement(bank);        
				} else {
					bank = this.selectedBanks.pushElement(bank);
				}

				this.view.addRow({
					prevIDArr: idArr,
					newID: bank.id,
					currentBank: bank.name,
					availableBanks: this.banks,
					totalElements: this.selectedBanks.getLength()
				});

	
			
			} else if (action === 'delete'){

				let row = button.closest('tr');
				let id = row ? parseInt(row.getAttribute('data-id')) : null

				let bank = this.selectedBanks.getElementByID(id);
				
				if ( bank === undefined){
					return false;
				}
				
				this.selectedBanks.removeElementByID(id)
		
				this.banks.push(bank.name);

				let idArr = this.selectedBanks.getAll().map((el) => el.id);

				this.view.deleteRow({
					prevIDArr: idArr,
					deleteID: id,
					availableBanks: this.banks,
					totalElements: this.selectedBanks.getLength()
				});				
			}
		}
   	},
	changeOnModal({target}){

		if (this.banks.length === 0){
			return;
		}

		let row = target.closest('tr');
		let id = row && row.getAttribute('data-id') ? parseInt(row.getAttribute('data-id')) : null
        
		if (id !== null){
			// Old bank selected
			let bank = this.selectedBanks.getElementByID(id);

			// New Bank selected
            let index = target.selectedIndex;
            let newSelectedValue = target.options[index].value;

			// Old value is pushed again in banks array
			this.banks.push(bank.name);
            
			// Remove the new value from available banks
			let indexNew = this.banks.indexOf(newSelectedValue);
			this.banks.splice(indexNew, 1);
	
			// Set the new value in old value select
			let indexOld = this.selectedBanks.getIndexByID(id)
			this.selectedBanks.setElementAtIndex(indexOld, { name: newSelectedValue});
			
			console.log(this.selectedBanks.getAll())

			this.view.changeSelect({
				prevIDArr: this.selectedBanks.getAll().map((el) => el.id),
				availableBanks: this.banks
			})
        }
	},
	setView(view){
		this.view = view;
	}
}

const SalePointModalPresenter = function (currency){
   this.view = null;
	this.currency = currency;
	this.banks = [];
	this.selectedBanks = new BankCollection();

	fetchInitialData()
		.then(res => {
			this.banks = res.banks;
		})
		.catch(err => {
			console.log(err)
		});

	async function fetchInitialData(){
        try {
            const banks = await getAllBanks();
            return {banks}
        } catch(e){
            return { banks: [] }
        }
   	}	
}

SalePointModalPresenter.prototype = SalePointModalPresenterPrototype;
SalePointModalPresenter.prototype.constructor = SalePointModalPresenter;

export default SalePointModalPresenter;