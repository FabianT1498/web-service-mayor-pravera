import PubSub from "pubsub-js";

import DenominationsTable from '_components/denominations-table'

const DenominationModalViewPrototype = {
    init(container, tableName){
        const tableContainer = container.querySelector('table')
        this.table = new DenominationsTable();
        this.table.init(tableContainer, this.name, this.presenter.currency);
        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
    },
    getTotal(){
        const total = this.table.getTotal();
        console.log(total)
    },
    clickEventHandlerWrapper(presenter){
        return (event) => {
            presenter.clickOnModal({
                target: event.target
            })
        }
    }
}

const DenominationModalView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this)
}

DenominationModalView.prototype = DenominationModalViewPrototype;
DenominationModalView.prototype.constructor = DenominationModalView;

export default DenominationModalView;