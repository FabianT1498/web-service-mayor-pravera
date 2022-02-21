const CashRegisterDataPrototype = {
    /**
     * Add event listeners to container
     * @function init
     * @param {Element} container - The DOM Element that wrapping many inputs.
     * @constructor
     */
    init(container){
        container.addEventListener("change", this.changeEventHandlerWrapper(container));
    },
    changeEventHandlerWrapper(container){
        return (event) => {
            
            let workersSelectEl = container.querySelector('#cash_register_worker');
            let newCashRegisterWorkerContainer = container.querySelector('#new_cash_register_worker_container');
            
            if (newCashRegisterWorkerContainer && workersSelectEl){
                newCashRegisterWorkerContainer.classList.toggle('hidden');
                workersSelectEl.disabled = !workersSelectEl.disabled;
                newCashRegisterWorkerContainer?.lastElementChild?.toggleAttribute('required');
                
                if (workersSelectEl.disabled){
                    workersSelectEl.selectedIndex = "0"
                }
            }
        }
    }
}

/**
 * It represents the cash register data component
 * @constructor
 */
const CashRegisterData = function (){

}

CashRegisterData.prototype = CashRegisterDataPrototype;
CashRegisterData.prototype.constructor = CashRegisterData;

export default CashRegisterData;