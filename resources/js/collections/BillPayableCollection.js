import ObjectCollection from "./objectCollection"

const BillPayableCollection = function(elements = []) {
    ObjectCollection.call(this, elements);

    this.removeElementByBillPayableData = function(numeroD, codProv)  {
        let index = this.elements.findIndex((val) => val.numeroD === numeroD && val.codProv === codProv);

        if (index === -1){ return false }

        this.elements.splice(index, 1);

        return true;
    }
}

BillPayableCollection.prototype = Object.create(ObjectCollection.prototype);
BillPayableCollection.prototype.constructor = BillPayableCollection;

export default BillPayableCollection;