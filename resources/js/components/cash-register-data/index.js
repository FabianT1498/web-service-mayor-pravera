const CashRegisterDataPrototype = {
    init(container){
        container.addEventListener("change", this.changeEventHandlerWrapper(container));
    },
    changeEventHandlerWrapper(container){
        return (event) => {
            const action = event.target.getAttribute('data-action');
            
            if(action === 'checkIfWorkerExist'){
                let workersSelectEl = container.querySelector('select[data-select="worker"]');
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
}

const CashRegisterData = function (){

}

CashRegisterData.prototype = CashRegisterDataPrototype;
CashRegisterData.prototype.constructor = CashRegisterData;

export default CashRegisterData;