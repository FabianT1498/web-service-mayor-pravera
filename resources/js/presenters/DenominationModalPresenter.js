import DenominationRecordCollection from '_collections/denominationRecordCollection'
import DenominationRecord from '_models/DenominationRecord'

import CURRENCIES_DENOMINATIONS from '_constants/currenciesDenominations'

const DenominationModalPresenterPrototype = {
	clickOnModal({ target }) {
		const closest = target.closest('button');

      	if(closest && closest.tagName === 'BUTTON'){
          const modaToggleID = closest.getAttribute('data-modal-toggle');
          
        	if (modaToggleID){ // Checking if it's closing the modal
				const total = this.denominationRecord.getAll().reduce((acc, curr) => acc + curr.total, 0)
				console.log(total)
        	}
      	}
   },
   keyPressedOnModal({target, key}){
		if (isFinite(key)){
			let denomination = target.getAttribute('data-denomination');
			this.updateDenominationRecord(denomination, target.value)
		}
	},
	keyDownOnModal({target, key}){
		if (key === 8 || key === 'Backspace'){
			let denomination = target.getAttribute('data-denomination');
			this.updateDenominationRecord(denomination, target.value)
		}
	},
	setView(view){
		this.view = view;
	},
	updateDenominationRecord(denomination, amount){
		if (isNaN(amount) || amount === ''){
			amount = 0
		}

		let amountInt = parseInt(amount);
		let denominationFloat = parseFloat(denomination);
		let index = this.denominationRecord.getIndexByDenomination(denominationFloat);
		let total = this.calculateTotal(amountInt, denominationFloat);
		
		if (index !== -1){
			this.denominationRecord.setElementAtIndex(index, {amount: amountInt, total})
			console.log(this.denominationRecord.getElementByIndex(index))
		}
	},
	calculateTotal(amount, denomination){
		return (Math.round(((denomination * amount) + Number.EPSILON) * 100) / 100)
	}
}

const DenominationModalPresenter = function (currency, method){
   	this.view = null;
	this.currency = currency;
	this.method = method;

	let denominationRecord = CURRENCIES_DENOMINATIONS[currency].map((el, index) => new DenominationRecord(currency, el, 0, 0, index))
	this.denominationRecord = new DenominationRecordCollection(denominationRecord)
}

DenominationModalPresenter.prototype = DenominationModalPresenterPrototype;
DenominationModalPresenter.prototype.constructor = DenominationModalPresenter;

export default DenominationModalPresenter;