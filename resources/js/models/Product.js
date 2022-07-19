const Product = function(codProd, descrip){
	this.codProd = codProd;
    this.descrip = descrip;
}

Product.prototype.constructor = Product;

export default Product;