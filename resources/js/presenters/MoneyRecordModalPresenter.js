import MoneyRecordCollection from '_collections/moneyRecordCollection'
import MoneyRecord from '_models/moneyRecord'

import { formatAmount } from '_utilities/mathUtilities'

const MoneyRecordModalPresenterPrototype = {
	clickOnModal({ target }) {
		const button = target.closest('button');

		if(button && button.tagName === 'BUTTON'){
		  const rowID = button.getAttribute('data-del-row');
		  const modalToggleID = button.getAttribute('data-modal-toggle');
		  
		  if (rowID){ // Delete a row
			  	if (this.moneyRecordCollection.getLength() === 1){
			  		// Clean the remaining row
					this.moneyRecordCollection.setElementAtIndex(0, {amount: 0})
			  		let record = this.moneyRecordCollection.getElementByIndex(0);
			  		this.view.resetLastInput(record.id);
			  	} else {
			  		// Delete the entry with the id
			  		let id = parseInt(rowID);
			  		this.moneyRecordCollection.removeElementByID(id);
			  		this.view.deleteRow(rowID)
			  	}
		  	} else if (modalToggleID){ // Checking if it's closing the modal
				const total = this.moneyRecordCollection.getAll().reduce((acc, curr) => acc + curr.amount, 0)
				this.setTotalAmount(total)
		  	}
		}
   },
   keyPressedOnModal({target, key}){
   		if (key === 13 || key === 'Enter'){ // Handle new table's row creation
			let amount = formatAmount(target.value);
			
			if (amount <= 0){ // If target value is zero, then don't to create a new row
				return;
			}

   			let moneyRecord = new MoneyRecord(0, this.currency, this.method);
   			moneyRecord = this.moneyRecordCollection.pushElement(moneyRecord)
            this.view.addRow({ ...moneyRecord, total: this.moneyRecordCollection.getLength()});
        } else if (isFinite(key)){
			let id = target.closest('tr').getAttribute('data-id');
			this.updateMoneyRecord(parseInt(id), target.value)
		}
   	},
	keyDownOnModal({target, key}){
		if (key === 8 || key === 'Backspace'){
            let id = target.closest('tr').getAttribute('data-id');
			this.updateMoneyRecord(parseInt(id), target.value)
        }
	},
	setView(view){
		this.view = view;
	},
	updateMoneyRecord(id, inputValue){
		let index = this.moneyRecordCollection.getIndexByID(parseInt(id));
		let value = formatAmount(inputValue);
		this.moneyRecordCollection.setElementAtIndex(index, { amount: value })
	}
}

const MoneyRecordModalPresenter = function (currency, method, setTotalAmount){
    this.view = null;
	this.currency = currency;
	this.method = method;
	this.moneyRecordCollection = new MoneyRecordCollection([new MoneyRecord(0.00, this.currency, this.method, 0)]);
	this.setTotalAmount = setTotalAmount;
}

MoneyRecordModalPresenter.prototype = MoneyRecordModalPresenterPrototype;
MoneyRecordModalPresenter.prototype.constructor = MoneyRecordModalPresenter;

export default MoneyRecordModalPresenter;