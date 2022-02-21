import Collection from './collection'
import ObjectCollection from "./objectCollection"

const MoneyRecordCollection = function(moneyRecords = []) {
    ObjectCollection.call(this);
    this.elements = moneyRecords;

    this.pushElement = function(el){
        el.id = this.getNewID();
        Collection.prototype.pushElement.call(this, el);
        return el;
    }
}

MoneyRecordCollection.prototype = Object.create(ObjectCollection.prototype);
MoneyRecordCollection.prototype.constructor = MoneyRecordCollection;

export default MoneyRecordCollection;