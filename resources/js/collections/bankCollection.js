import Collection from "./collection";

const BankCollection = function(banks = []) {
    Collection.call(this);
    this.elements = banks ? banks : [];    
}

BankCollection.prototype = Object.create(Collection.prototype);
BankCollection.prototype.constructor = BankCollection;

export default BankCollection;