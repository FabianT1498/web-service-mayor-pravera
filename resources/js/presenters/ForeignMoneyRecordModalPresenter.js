import MoneyRecordModalPresenter from './MoneyRecordModalPresenter';

import { CURRENCIES, SIGN as CURRENCY_SYMBOLS_MAP } from '_constants/currencies'

import { formatAmount } from '_utilities/mathUtilities'

import { store } from '_store'
import { STORE_DOLLAR_EXCHANGE_VALUE } from '_store/action'

const ForeignMoneyRecordModalPresenter = function (currency, method){
	MoneyRecordModalPresenter.call(this, currency, method);

	store.subscribe(() => {
		let state = store.getState();
	
		if (state.lastAction === STORE_DOLLAR_EXCHANGE_VALUE && this.moneyRecordCollection.getLength() > 0){
			let convertions = getAllConvertions(this.moneyRecordCollection.getAll(), state.dollarExchange.value)
			this.view.updateConvertionCol(convertions)
		}
	})

	this.keyPressedOnModal = function({target, key}){
		MoneyRecordModalPresenter.prototype.keyPressedOnModal.call(this, {target, key})
 
		if (isFinite(key)){ // Handle case to convert dollar to Bs.S`
			let rowID = target.closest('tr').getAttribute('data-id');
            let amount = formatAmount(target.value)
			let dollarExchangeValue = store.getState().dollarExchange.value;
            let formatedConvertion = getConvertionFormated(amount, dollarExchangeValue)
			this.view.updateConvertion({rowID, formatedConvertion});
		}
	}

	this.keyDownOnModal = function({target, key}){
		MoneyRecordModalPresenter.prototype.keyDownOnModal.call(this, {target, key})
		if (key === 8 || key === 'Backspace'){
            let rowID = target.closest('tr').getAttribute('data-id');
			let amount = formatAmount(target.value)
			let dollarExchangeValue = store.getState().dollarExchange.value;
            let formatedConvertion = getConvertionFormated(amount, dollarExchangeValue)
			this.view.updateConvertion({rowID, formatedConvertion});
        }
	}

	const getConvertionFormated = function(amount, dollarExchange) {	
		return `${calculateConvertion(amount, dollarExchange)} ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}`;
	}

	const calculateConvertion = function(amount, exchangeValue){
		return (Math.round(((exchangeValue * amount) + Number.EPSILON) * 100) / 100)
	}

	const getAllConvertions =  function(records, exchangeValue){
		return records.map(el => getConvertionFormated(el.amount, exchangeValue))
	}
}

ForeignMoneyRecordModalPresenter.prototype = Object.create(MoneyRecordModalPresenter.prototype)
ForeignMoneyRecordModalPresenter.prototype.constructor = ForeignMoneyRecordModalPresenter;

export default ForeignMoneyRecordModalPresenter;