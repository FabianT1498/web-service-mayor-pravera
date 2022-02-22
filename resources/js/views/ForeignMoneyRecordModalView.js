import MoneyRecordModalView from './MoneyRecordModalView'
import ForeignMoneyRecordTable from '_components/money-record-table/ForeignMoneyRecordTable'

const ForeignMoneyRecordModalView = function(presenter) {
    MoneyRecordModalView.call(this, presenter);

    this.updateConvertionCol = function(obj){
        this.table.updateConvertionCol(obj)
    }

    this.init = function(container, name, table){
        MoneyRecordModalView.prototype.init.call(this, container, name, table)
    }
}

ForeignMoneyRecordModalView.prototype = Object.create(MoneyRecordModalView.prototype)
ForeignMoneyRecordModalView.prototype.constructor = ForeignMoneyRecordModalView;

export default ForeignMoneyRecordModalView;