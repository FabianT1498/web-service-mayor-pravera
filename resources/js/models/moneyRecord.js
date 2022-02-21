const MoneyRecord = function(amount, currency, method, id = 0){
	this.id = id;
	this.amount = amount;
	this.currency = currency;
	this.method = method
}