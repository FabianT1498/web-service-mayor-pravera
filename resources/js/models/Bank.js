const Bank = function(name, id = null){
	this.id = id;
	this.name = name;
}

Bank.prototype.constructor = Bank;

export default Bank;