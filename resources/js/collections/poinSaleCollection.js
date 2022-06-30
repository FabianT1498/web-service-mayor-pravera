import ObjectCollection from "./objectCollection"

const PointSaleCollection = function(elements = []) {
    ObjectCollection.call(this, elements);

    this.removeElementByBankID = function(bank)  {
        let index = this.elements.findIndex((val) => val.bank.id === bank.id);

        if (index === -1){ return false }

        this.elements.splice(index, 1);

        return true;
    }

    this.getIndexByBankID = function(bank)  {
        return this.elements.findIndex((val) => val.bank.id === bank.id);
    }
}



PointSaleCollection.prototype = Object.create(ObjectCollection.prototype);
PointSaleCollection.prototype.constructor = PointSaleCollection;

export default PointSaleCollection;