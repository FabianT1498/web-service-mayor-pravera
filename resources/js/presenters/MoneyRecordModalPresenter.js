import MoneyRecordCollection from '_collections/moneyRecordCollection'
import MoneyRecord from '_models/moneyRecord'

const MoneyRecordModalPresenterPrototype = {
	init: function(currency, method){
		this.currency = currency;
		this.method = method;
		this.moneyRecordCollection = new MoneyRecordCollection([]);
	},
	clickOnModal({ target }) {
		const button = target.closest('button');

		console.log('hola')

		if(button && button.tagName === 'BUTTON'){
		  const rowID = button.getAttribute('data-del-row');
		  const modalToggleID = button.getAttribute('data-modal-toggle');
		  
		  if (rowID){ // Delete a row
			  	if (this.moneyRecordCollection.getLength() === 1){
			  		// Clean the remaining row
			  		let record = this.moneyRecordCollection.getAll()[0];
			  		this.view.resetLastInput(record.id);
			  	} else {
			  		// Delete the entry with the id
			  		let id = parseInt(rowID);
			  		this.moneyRecordCollection.removeElementByID(id);
			  		this.view.deleteRow(rowID)
			  	}
		  	} else if (modalToggleID){ // Checking if it's closing the modal
		      // console.log(`getTotal.records.${method}.${currency}`)
		      // PubSub.publish(`getTotal.records.${method}.${currency}`);
		  }
		}
   },
   keyPressedOnModal({key}){
   		if (key === 13 || key === 'Enter'){ // Handle new table's row creation
   			let moneyRecord = new MoneyRecord(0.00, this.currency, this.method);
   			moneyRecord = this.moneyRecordCollection.pushElement(moneyRecord)
            this.view.addRow({ ...moneyRecord, total: this.moneyRecordCollection.getLength()});
        }
   	}
}

const MoneyRecordModalPresenter = function (view){
    this.view = view;
}

MoneyRecordModalPresenter.prototype = MoneyRecordModalPresenterPrototype;
MoneyRecordModalPresenter.prototype.constructor = MoneyRecordModalPresenter;

export default MoneyRecordModalPresenter;