const BillPayable = function(numeroD, codProv, id = null){
	this.id = id;
	this.numeroD = numeroD;
	this.codProv = codProv;
}

BillPayable.prototype.constructor = BillPayable;

export default BillPayable;