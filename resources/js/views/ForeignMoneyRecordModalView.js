import MoneyRecordModalView from './MoneyRecordModalView'

const ForeignMoneyRecordModalViewPrototype = {
    updateConvertionCol(obj){
        // this.table.updateConvertionCol(obj)
        console.log(obj)
    }
}

const ForeignMoneyRecordModalView = function(presenter) {
    MoneyRecordModalView.call(this, presenter);
}

ForeignMoneyRecordModalView.prototype = Object.create(ForeignMoneyRecordModalViewPrototype.prototype);
ForeignMoneyRecordModalView.prototype.constructor = ForeignMoneyRecordModalView;

export default ForeignMoneyRecordModalView;