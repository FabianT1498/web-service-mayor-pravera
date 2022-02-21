import MoneyRecordCollection from '_collections/moneyRecordCollection'
import MoneyRecord from '_models/moneyRecord'

const MoneyRecordModalPresenterPrototype = {
	clickOnModal({ target }) {
		const button = target.closest('button');

		if(button && button.tagName === 'BUTTON'){
		  const rowID = button.getAttribute('data-del-row');
		  const modalToggleID = button.getAttribute('data-modal-toggle');
		  
		  if (rowID){ // Delete a row
			  	if (this.moneyRecordCollection.getLength() === 1){
			  		// Clean the remaining row
			  		let record = this.moneyRecordCollection.getAll()[0];
			  		this.view.resetLastInput(record.id);
					console.log('Last record')
					console.log(this.moneyRecordCollection.getAll())
			  	} else {
			  		// Delete the entry with the id
			  		let id = parseInt(rowID);
			  		this.moneyRecordCollection.removeElementByID(id);
			  		this.view.deleteRow(rowID)
					console.log('Deleted record')
					console.log(this.moneyRecordCollection.getAll())
			  	}
		  	} else if (modalToggleID){ // Checking if it's closing the modal
		      // console.log(`getTotal.records.${method}.${currency}`)
		      // PubSub.publish(`getTotal.records.${method}.${currency}`);
		  }
		}
   },
   keyPressedOnModal({target}){
	   	let key = target.key || target.keyCode
		   console.log(target)
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
	}
}

const MoneyRecordModalPresenter = function (currency, method){
    this.view = null;
	this.currency = currency;
	this.method = method;
	this.moneyRecordCollection = new MoneyRecordCollection([new MoneyRecord(0.00, this.currency, this.method)]);
}

MoneyRecordModalPresenter.prototype = MoneyRecordModalPresenterPrototype;
MoneyRecordModalPresenter.prototype.constructor = MoneyRecordModalPresenter;

export default MoneyRecordModalPresenter;