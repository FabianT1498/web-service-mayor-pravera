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
                    PubSub.publish(`getTotal.${currency}`);
                }
            }
        }
    } 
}

const LiquidMoneyModal = function (){

}

LiquidMoneyModal.prototype = LiquidMoneyModalPrototype;
LiquidMoneyModal.prototype.constructor = LiquidMoneyModal;

// LiquidMoneyModal.prototype.init =  function (container, tableName){
//     container.addEventListener("keypress", this.keypressEventHandler);
//     container.addEventListener("click", this.clickEventHandler);

//     const tableContainer = container.querySelector('table')
//     const table = new CashRegisterTable(tableName);
//     table.init(tableContainer);
// }

// LiquidMoneyModal.prototype.clickEventHandler = function clickEventHandler(event){
//     const button = event.target.closest('button');

//     if(button && button.tagName === 'BUTTON'){

//         const rowID = button.getAttribute('data-del-row');
//         const modalToggleID = button.getAttribute('data-modal-toggle');
        
//         if (rowID){ // Checking if it's Deleting a row
//             const row = button.closest('tr');
//             PubSub.publish('deleteRow', { row, rowID});
//         } else if (modalToggleID){ // Checking if it's closing the modal

//             // get all inputs of the modal
//             let inputs = document.querySelectorAll(`#${this.id} input`)
//             const total = Array.from(inputs).reduce((acc, el) => {
//                 let num = formatAmount(el.value)
//                 return acc + num;
//             }, 0);

//             document.getElementById(`total_${this.id}`).value = total > 0 ? total : 0;
//         }
//     }
// }

export default LiquidMoneyModal;