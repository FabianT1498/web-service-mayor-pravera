import ObjectCollection from "./objectCollection"

const DenominationRecordCollection = function(elements = []) {
    ObjectCollection.call(this, elements);

    this.getIndexByDenomination = function(denomination)  {
        return this.elements.findIndex((val) => val.denomination === denomination);
    }
}

DenominationRecordCollection.prototype = Object.create(ObjectCollection.prototype);
DenominationRecordCollection.prototype.constructor = DenominationRecordCollection;

export default DenominationRecordCollection;