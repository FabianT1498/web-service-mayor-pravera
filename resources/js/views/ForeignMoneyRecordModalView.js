import MoneyRecordModalView from './MoneyRecordModalView'

const ForeignMoneyRecordModalView = function(presenter) {
    MoneyRecordModalView.call(this, presenter);

    this.updateConvertion = function(obj){
        this.table.updateConvertion(obj)
    }

    this.updateConvertionCol = function(convertions){
        this.table.updateConvertionCol(convertions)
    }

    this.init = function(container, name, table){
        MoneyRecordModalView.prototype.init.call(this, container, name, table)
    }
}

ForeignMoneyRecordModalView.prototype = Object.create(MoneyRecordModalView.prototype)
ForeignMoneyRecordModalView.prototype.constructor = ForeignMoneyRecordModalView;

export default ForeignMoneyRecordModalView;