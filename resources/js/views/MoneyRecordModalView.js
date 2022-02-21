import PubSub from "pubsub-js";

import MoneyRecordTable from '_components/cash-register-table'
import MoneyRecordModalPresenter from '_presenters/MoneyRecordModalPresenter'

const MoneyRecordModalViewPrototype = {
    init(container, name){
        const tableContainer = container.querySelector('table')
        const table = new MoneyRecordTable(name, this.currency);
        
        
        container.addEventListener("keypress", function(){
            let presenter = this.presenter
            return (event) => {
                event.preventDefault();
                presenter.keyPressedOnModal({
                    key: event.key || event.keyCode
                })
            }
        })

        container.addEventListener("click", function(){
            let presenter = this.presenter;
            return (event) => {
                presenter.clickOnModal({
                    target: event.target
                })
            }
        });
    },
    resetLastInput(id){
        this.table.resetLastInput(id)
    },
    addRow(obj){
        this.table.addRow(obj);
    },
    deleteRow(id){
        this.table.deleteRow(id);
    },
}

const MoneyRecordModalView = function (currency, method){
    this.currency = currency;
    console.log(this)
    this.presenter = new MoneyRecordModalPresenter(this);
    this.presenter.init(currency, method);
}

MoneyRecordModalView.prototype = MoneyRecordModalViewPrototype;
MoneyRecordModalView.prototype.constructor = MoneyRecordModalView;

export default MoneyRecordModalView;