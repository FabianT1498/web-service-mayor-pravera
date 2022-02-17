

export default function(){

    // --- HANDLING INPUTS TO CREATE A NEW CASH REGISTER WORKER ---
    
    const existCashRegisterWorker = document.getElementById('exist_cash_register_worker');
    const cashRegisterWorkerSelect = document.getElementById('cash_register_worker');
    const newCashRegisterWorkerContainer = document.getElementById('hidden-new-cash-register-worker-container');
    
    const handleChangeExistWorker = function(event) {
        newCashRegisterWorkerContainer.classList.toggle('hidden');
        cashRegisterWorkerSelect.disabled = !cashRegisterWorkerSelect.disabled;
        newCashRegisterWorkerContainer.lastElementChild.toggleAttribute('required');
        
        if (cashRegisterWorkerSelect.disabled){
            cashRegisterWorkerSelect.selectedIndex = "0"
        }
    }

    existCashRegisterWorker.addEventListener('change', handleChangeExistWorker);
    
    // --- HANDLING FORM SUBMIT ---
    const form = document.querySelector('#form');

    const submit = (event) => {
        let allIsNull = true;
      
        for(let i = 0; i < inputs.length; i++){
            let el = inputs[i];
            
            if (el.value){
                allIsNull = false;
                break;
            }
        }
       
        // Check if there's at least one input filled
        if (allIsNull){
            event.preventDefault();
            alert('Epa, no se ha ingresado ningun ingreso')
            return;
        }
    }
    
    form.addEventListener('submit', submit);

    // --- HANDLING INPUT MASKS ---
    let inputs = document.querySelectorAll('[data-currency^="amount"]');

}