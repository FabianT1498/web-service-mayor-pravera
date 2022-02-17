import PubSub from "pubsub-js";

import DenominationsTable from '_components/denominations-table'

const DenominationsModalPrototype = {
    init(container){
        const tableContainer = container.querySelector('table')
        const table = new DenominationsTable(this.name, this.currency);
        table.init(tableContainer);
        container.addEventListener("click", this.clickEventHandlerWrapper(this.currency));
    },
    clickEventHandlerWrapper(currency){
        return (event) => {
            const closest = event.target.closest('button');

            if(closest && closest.tagName === 'BUTTON'){

                const modaToggleID = closest.getAttribute('data-modal-toggle');
                
                if (modaToggleID){ // Checking if it's closing the modal

                    // get all inputs of the modal
                    PubSub.publish(`getTotal.denominations.${currency}`);
                }
            }
        }
    }
}

const DenominationsModal = function (name, currency){
    this.name = name;
    this.currency = currency;
}

DenominationsModal.prototype = DenominationsModalPrototype;
DenominationsModal.prototype.constructor = DenominationsModal;

export default DenominationsModal;