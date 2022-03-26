import SalePointTable from "_components/sale-point-table";

const SalePointModalViewPrototype = {
    init(container, tableName){
        const tableContainer = container.querySelector('table')
        this.table = new SalePointTable();
        this.table.init(tableContainer, tableName, this.presenter.currency);

        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
        container.addEventListener('change', this.changeEventHandlerWrapper(this.presenter));
        container.addEventListener("keypress", this.keyPressEventHandlerWrapper(this.presenter))
        container.addEventListener("keydown", this.keyDownEventHandlerWrapper(this.presenter));

    },
    addRow(obj){
        this.table.addRow(obj);
    },
    deleteRow(obj){
        this.table.deleteRow(obj);
    },
    changeSelect(obj){
        this.table.changeSelect(obj)
    },
    clickEventHandlerWrapper(presenter){
        return (event) => {
            presenter.clickOnModal({
                target: event.target
            })
        }
    },
    changeEventHandlerWrapper(presenter){
        return (event) => {
            presenter.changeOnModal({
                target: event.target
            })
        }
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
    keyDownEventHandlerWrapper(presenter){
        return (event) => {
            this.presenter.keyDownOnModal({
                target: event.target,
                key: event.key || event.keyCode
            });
        }
    }
}

const SalePointModalView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this)
}

SalePointModalView.prototype = SalePointModalViewPrototype;
SalePointModalView.prototype.constructor = SalePointModalView;

export default SalePointModalView;