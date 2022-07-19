import ObjectCollection from "./objectCollection"

const SuggestionCollection = function(elements = []) {
    ObjectCollection.call(this, elements);
}

SuggestionCollection.prototype = Object.create(ObjectCollection.prototype);
SuggestionCollection.prototype.constructor = SuggestionCollection;

export default SuggestionCollection;