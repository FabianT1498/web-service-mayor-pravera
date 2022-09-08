const BillPayable = function(numeroD, codProv, provDescrip, billType, tasa, amount, isDollar, fechaE, id = null){
	this.id = id;
	this.numeroD = numeroD;
	this.codProv = codProv;
	this.provDescrip = provDescrip;
	this.billType = billType;
	this.tasa = tasa;
	this.amount = amount;
	this.isDollar = isDollar;
	this.fechaE = fechaE;
}

BillPayable.prototype.constructor = BillPayable;

export default BillPayable;