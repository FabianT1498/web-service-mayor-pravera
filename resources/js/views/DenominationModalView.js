import DenominationsTable from '_components/denominations-table'

const DenominationModalViewPrototype = {
    init(container, tableName){
        const tableContainer = container.querySelector('table')
        this.table = new DenominationsTable();
        this.table.init(tableContainer, tableName, this.presenter.currency);
        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
        container.addEventListener("keypress", this.keypressEventHandlerWrapper(this.presenter));
        container.addEventListener("keydown", this.keydownEventHandlerWrapper(this.presenter));
    },
    clickEventHandlerWrapper(presenter){
        return (event) => {
            presenter.clickOnModal({
                target: event.target
            })
        }
    },
    keypressEventHandlerWrapper(presenter){
        return (event) => {
            presenter.keyPressedOnModal({
                key: event.key,
                target: event.target
            })
        }
    },
    keydownEventHandlerWrapper(presenter){
        return (event) => {
            presenter.keyDownOnModal({
                key: event.key,
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