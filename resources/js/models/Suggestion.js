const Suggestion = function(codProd, percentSuggested, username, createdAt, status, id = null){
	this.id = id;
	this.codProd = codProd;
    this.percentSuggested = percentSuggested;
	this.username = username;
	this.status = status,
	this.createdAt = createdAt
}

Suggestion.prototype.constructor = Suggestion;

export default Suggestion;