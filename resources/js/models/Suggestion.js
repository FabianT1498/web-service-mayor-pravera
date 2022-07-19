const Suggestion = function(codProd, percentSuggested, username, createdAt, id = null){
	this.id = id;
	this.codProd = codProd;
    this.percentSuggested = percentSuggested;
	this.username = username;
	this.createdAt = createdAt
}

Suggestion.prototype.constructor = Suggestion;

export default Suggestion;