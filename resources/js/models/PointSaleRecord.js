import Bank from './Bank'

const PointSaleRecord = function(currency, total = 0, bank, id = null){
	this.id = id;
    this.total = total;
    this.currency = currency;
    this.bank = bank instanceof Bank ? bank : null 
}

PointSaleRecord.prototype.constructor = PointSaleRecord;

export default PointSaleRecord;