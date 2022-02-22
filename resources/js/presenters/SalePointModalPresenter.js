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
         	if (this.banks.length() === 0){
         		return;
         	}
  				
  				let idArr = [];
  				let newID = 0;
  				let bank = new Bank(this.banks.shift());

	        	if (this.selectedBanks.getLength() > 0){
	           	idArr = this.selectedBanks.getAll().map((el) => el.id);
	           	console.log(idArr); 	
	           	bank = this.selectedBanks.pushElement(bank);        
	        } else {
	            bank = this.selectedBanks.pushElement(bank);
	        }

         	this.view.addRow({
         		prevIDArr: idArr,
         		newID: bank.id,
         		availableBanks: this.banks,
         		totalElements: this.selectedBanks.getLength()
         	});
 
         } else if (action === 'delete'){
             // let row = button.closest('tr');
             // let rowID = row ? row.getAttribute('data-id') : null
             // PubSub.publish('deleteRow.salePoint', {row, rowID});
         }
		}
   },
   keyPressedOnModal({target, key}){
   		if (key === 13 || key === 'Enter'){ // Handle new table's row creation
   			let moneyRecord = new MoneyRecord(0.00, this.currency, this.method);
   			moneyRecord = this.moneyRecordCollection.pushElement(moneyRecord)
            this.view.addRow({ ...moneyRecord, total: this.moneyRecordCollection.getLength()});
			console.log('Record Added')
			console.log(this.moneyRecordCollection.getAll())
        }
   	},
	setView(view){
		this.view = view;
	},
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

	const fetchInitialData = async function(){
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