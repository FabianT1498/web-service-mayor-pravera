const BillPayable = function(numeroD, codProv, provDescrip, id = null){
	this.id = id;
	this.numeroD = numeroD;
	this.codProv = codProv;
	this.provDescrip = provDescrip;
}

BillPayable.prototype.constructor = BillPayable;

export default BillPayable;