const DenominationRecord = function(currency, denomination, total = 0, amount = 0, id = null){
	this.id = id;
	this.amount = amount;
    this.total = total;
    this.currency = currency;
    this.denomination = denomination;
}

DenominationRecord.prototype.constructor = DenominationRecord;

export default DenominationRecord;