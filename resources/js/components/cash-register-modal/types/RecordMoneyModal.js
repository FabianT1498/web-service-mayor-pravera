import PubSub from "pubsub-js";

import CashRegisterTable from '_components/cash-register-table'

const RecordMoneyModalPrototype = {
    init(container, tableName){
        
        container.addEventListener("keypress", this.keypressEventHandlerWrapper(this.currency, this.method));
        container.addEventListener("click", this.clickEventHandlerWrapper(this.currency, this.method));
    
        const tableContainer = container.querySelector('table')
        const table = new CashRegisterTable(tableName, this.currency, this.method);
        table.init(tableContainer);
    },
    clickEventHandlerWrapper(currency, method){ // Closure
        return (event) => {
            const button = event.target.closest('button');
            if(button && button.tagName === 'BUTTON'){
                const rowID = button.getAttribute('data-del-row');
                const modalToggleID = button.getAttribute('data-modal-toggle');
                
                if (rowID){ // Checking if it's Deleting a row
                    const row = button.closest('tr');
                    PubSub.publish(`deleteRow.${method}.${currency}`, { row, rowID});
                } else if (modalToggleID){ // Checking if it's closing the modal
                    console.log(`getTotal.records.${method}.${currency}`)
                    PubSub.publish(`getTotal.records.${method}.${currency}`);
                }
            }
        }
    } 
}

const RecordMoneyModal = function (){

}

RecordMoneyModal.prototype = RecordMoneyModalPrototype;
RecordMoneyModal.prototype.constructor = RecordMoneyModal;

export default RecordMoneyModal;