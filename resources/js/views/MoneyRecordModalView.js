const MoneyRecordModalViewPrototype = {
    init(container, name, table){
        if (!container || !table || !this.presenter){
            return false;
        }

        let tableContainer = container.querySelector('table');
        this.table = table;
        this.table.init(tableContainer, name, this.presenter.currency);
        
        container.addEventListener("keypress", this.keyPressEventHandlerWrapper(this.presenter))
        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
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
    keyPressEventHandlerWrapper(presenter){
        return (event) => {
            event.preventDefault();
            presenter.keyPressedOnModal({
                target: event.target,
                key: event.key || event.keyCode
            })
        }
    },
    clickEventHandlerWrapper(presenter){
        return (event) => {
            presenter.clickOnModal({
                target: event.target
            })
        }
    }
}

const MoneyRecordModalView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

MoneyRecordModalView.prototype = MoneyRecordModalViewPrototype;
MoneyRecordModalView.prototype.constructor = MoneyRecordModalView;

export default MoneyRecordModalView;