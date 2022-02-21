import MoneyRecordModalPresenter from './MoneyRecordModalPresenter';

import { formatAmount } from '_utilities/mathUtilities'

import { CURRENCIES, SIGN as CURRENCY_SYMBOLS_MAP } from '_constants/currencies'

const ForeignMoneyRecordModalPresenter = function (currency, method){
	MoneyRecordModalPresenter.call(this, currency, method);

	this.keyPressedOnModal = function({target, key}){
		console.log(target);
		 MoneyRecordModalPresenter.prototype.keyPressedOnModal.call(this, {target, key})
 
		 if (isFinite(key)){ // Handle case to convert dollar to Bs.S`
			 console.log('Number pressed')
			 let rowID = target.closest('tr').getAttribute('data-id');
			 let inputValue = formatAmount(target.value)
			 let dollarExchangeValue = 4.30
			 let formatedConvertion = `${getConvertion(inputValue, dollarExchangeValue)} ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}`;
			 // Necesito recuperar el valo del dolar actual
			 this.view.updateConvertionCol({rowID, formatedConvertion});
		 }
	}

	const getConvertion = function(amount, exchangeValue){
		 return (Math.round(((exchangeValue * amount) + Number.EPSILON) * 100) / 100)
	}
}

ForeignMoneyRecordModalPresenter.prototype = Object.create(MoneyRecordModalPresenter.prototype)
ForeignMoneyRecordModalPresenter.prototype.constructor = ForeignMoneyRecordModalPresenter;

export default ForeignMoneyRecordModalPresenter;