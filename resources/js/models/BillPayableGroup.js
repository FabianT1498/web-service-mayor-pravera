const BillPayableGroup = function(id, status, cod_prov, totalAmount, paidAmount){
	this.id = id;
	this.status = status;
	this.codProv = cod_prov;
	this.totalAmount = totalAmount;
	this.paidAmount = paidAmount;
}

BillPayableGroup.prototype.constructor = BillPayableGroup;

export default BillPayableGroup;