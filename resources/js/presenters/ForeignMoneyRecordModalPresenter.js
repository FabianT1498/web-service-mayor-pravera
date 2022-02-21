import MoneyRecordModalPresenter from './MoneyRecordModalPresenter';

import { formatAmount } from '_utilities/mathUtilities'

const ForeignMoneyRecordModalPresenterPrototype = {
   keyPressedOnModal({target}){
		MoneyRecordModalPresenter.prototype.keyPressedOnModal.call(this, target)

		let key = target.key || target.keyCode

		if (isFinite(key)){ // Handle case to convert dollar to Bs.S
			let rowID = target.closest('tr').getAttribute('data-id');
			let inputValue = formatAmount(target.value)
			let dollarExchangeValue = 4.30
			let formatedConvertion = `${this.getConvertion(inputValue, dollarExchangeValue)} ${CURRENCY_SYMBOLS_MAP[this.currency]}`;
			// Necesito recuperar el valo del dolar actual
			this.view.updateConvertionCol({rowID, formatedConvertion});
		}
   	},
	getConvertion(amount, exchangeValue){
		return (Math.round(((exchangeValue * amount) + Number.EPSILON) * 100) / 100)
	}
}

const ForeignMoneyRecordModalPresenter = function (currency, method){
	MoneyRecordModalPresenter.call(this, currency, method);
}

ForeignMoneyRecordModalPresenter.prototype = Object.create(MoneyRecordModalPresenter.prototype)
ForeignMoneyRecordModalPresenter.prototype.constructor = ForeignMoneyRecordModalPresenter;

export default ForeignMoneyRecordModalPresenter;