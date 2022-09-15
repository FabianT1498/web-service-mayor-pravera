import ObjectCollection from "./objectCollection"

const BillPayableGroupCollection = function(elements = []) {
    ObjectCollection.call(this, elements);
}

BillPayableGroupCollection.prototype = Object.create(ObjectCollection.prototype);
BillPayableGroupCollection.prototype.constructor = BillPayableGroupCollection;

export default BillPayableGroupCollection;