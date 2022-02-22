import MoneyRecordModalPresenter from './MoneyRecordModalPresenter';

import { CURRENCIES, SIGN as CURRENCY_SYMBOLS_MAP } from '_constants/currencies'

import { formatAmount } from '_utilities/mathUtilities'

const ForeignMoneyRecordModalPresenter = function (currency, method){
	MoneyRecordModalPresenter.call(this, currency, method);

	this.keyPressedOnModal = function({target, key}){
		MoneyRecordModalPresenter.prototype.keyPressedOnModal.call(this, {target, key})
 
		if (isFinite(key)){ // Handle case to convert dollar to Bs.S`
			let rowID = target.closest('tr').getAttribute('data-id');
            let formatedConvertion = getConvertionFormated(target)
			this.view.updateConvertionCol({rowID, formatedConvertion});
		}
	}

	this.keyDownOnModal = function({target, key}){
		MoneyRecordModalPresenter.prototype.keyDownOnModal.call(this, {target, key})
		if (key === 8 || key === 'Backspace'){
            let rowID = target.closest('tr').getAttribute('data-id');
            let formatedConvertion = getConvertionFormated(target)
			this.view.updateConvertionCol({rowID, formatedConvertion});
        }
	}

	const getConvertionFormated = function(target) {
		let inputValue = formatAmount(target.value)
		let dollarExchangeValue = 4.30
		return `${calculateConvertion(inputValue, dollarExchangeValue)} ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}`;
	}

	const calculateConvertion = function(amount, exchangeValue){
		return (Math.round(((exchangeValue * amount) + Number.EPSILON) * 100) / 100)
	}
}

ForeignMoneyRecordModalPresenter.prototype = Object.create(MoneyRecordModalPresenter.prototype)
ForeignMoneyRecordModalPresenter.prototype.constructor = ForeignMoneyRecordModalPresenter;

export default ForeignMoneyRecordModalPresenter;