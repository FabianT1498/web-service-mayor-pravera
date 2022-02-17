import PubSub from "pubsub-js";

import CashRegisterTable from '_components/cash-register-table'

const LiquidMoneyModalPrototype = {
    init(container, tableName){
        
        container.addEventListener("keypress", this.keypressEventHandler);
        container.addEventListener("click", this.clickEventHandlerWrapper(this.currency));
    
        const tableContainer = container.querySelector('table')
        const table = new CashRegisterTable(tableName, this.currency);
        table.init(tableContainer);
    },
    clickEventHandlerWrapper(currency){ // Closure
        return (event) => {
            const button = event.target.closest('button');
            if(button && button.tagName === 'BUTTON'){
                const rowID = button.getAttribute('data-del-row');
                const modalToggleID = button.getAttribute('data-modal-toggle');
                
                if (rowID){ // Checking if it's Deleting a row
                    const row = button.closest('tr');
                    PubSub.publish(`deleteRow.${currency}`, { row, rowID});
                } else if (modalToggleID){ // Checking if it's closing the modal
                    PubSub.publish(`getTotal.records.${currency}`);
                }
            }
        }
    } 
}

const LiquidMoneyModal = function (){

}

LiquidMoneyModal.prototype = LiquidMoneyModalPrototype;
LiquidMoneyModal.prototype.constructor = LiquidMoneyModal;

export default LiquidMoneyModal;