import MoneyRecordModalView from './MoneyRecordModalView'
import ForeignMoneyRecordTable from '_components/money-record-table/ForeignMoneyRecordTable'

const ForeignMoneyRecordModalView = function(presenter) {
    MoneyRecordModalView.call(this, presenter);

    this.init = function(container, name){
        const tableContainer = container.querySelector('table')
        this.table = new ForeignMoneyRecordTable(name, this.presenter.currency);
        this.table.init(tableContainer);
        
        container.addEventListener("keypress", this.keyPressEventHandlerWrapper(this.presenter))
        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
    },

    this.updateConvertionCol = function(obj){
        this.table.updateConvertionCol(obj)
    }
}

ForeignMoneyRecordModalView.prototype = Object.create(MoneyRecordModalView.prototype)
ForeignMoneyRecordModalView.prototype.constructor = ForeignMoneyRecordModalView;

export default ForeignMoneyRecordModalView;