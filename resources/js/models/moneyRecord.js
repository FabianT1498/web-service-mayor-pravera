const MoneyRecord = function(amount, currency, method, id = null){
	this.id = id;
	this.amount = amount;
	this.currency = currency;
	this.method = method
}

MoneyRecord.prototype.constructor = MoneyRecord;

export default MoneyRecord;