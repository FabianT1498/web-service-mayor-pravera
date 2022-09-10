const BillPayableGroup = function(id = '', status = '', cod_prov = '', totalAmount = 0.00, paidAmount = 0.00){
	this.id = id;
	this.status = status;
	this.codProv = cod_prov;
	this.totalAmount = totalAmount;
	this.paidAmount = paidAmount;
}

BillPayableGroup.prototype.constructor = BillPayableGroup;

export default BillPayableGroup;