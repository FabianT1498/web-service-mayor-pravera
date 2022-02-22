import SalePointTable from "_components/sale-point-table";

const SalePointModalViewPrototype = {
    init(container, tableName){
        const tableContainer = container.querySelector('table')
        this.table = new DenominationsTable();
        this.table.init(tableContainer, this.name, this.presenter.currency);

        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
        container.addEventListener('change', this.changeEventHandlerWrapper(this.presenter));
    },
    addRow(obj){
        this.table.addRow(obj);
    },
    deleteRow(id){
        this.table.deleteRow(id);
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
            presenter.clickOnModal({
                target: event.target
            })
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