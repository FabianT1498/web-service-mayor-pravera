import Collection from './collection'
import ObjectCollection from "./objectCollection"

const MoneyRecordCollection = function(elements = []) {
    ObjectCollection.call(this, elements);
}

MoneyRecordCollection.prototype = Object.create(ObjectCollection.prototype);
MoneyRecordCollection.prototype.constructor = MoneyRecordCollection;

export default MoneyRecordCollection;