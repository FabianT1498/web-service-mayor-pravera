import ObjectCollection from "./objectCollection"

const BankCollection = function(elements = []) {
    ObjectCollection.call(this, elements);
}

BankCollection.prototype = Object.create(ObjectCollection.prototype);
BankCollection.prototype.constructor = BankCollection;

export default BankCollection;