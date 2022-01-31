import Inputmask from "inputmask";
import { reduce } from "lodash";

export default function(){
    let inputs = document.querySelectorAll('[data-currency^="amount"]');
    const existCashRegisterWorker = document.getElementById('exist_cash_register_worker');
    const cashRegisterWorkerSelect = document.getElementById('cash_register_worker');
    const newCashRegisterWorkerContainer = document.getElementById('hidden-new-cash-register-worker-container');

    let maskedInputs = [];

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

    const handleChangeExistWorker = function(event) {
        newCashRegisterWorkerContainer.classList.toggle('hidden');
        cashRegisterWorkerSelect.disabled = !cashRegisterWorkerSelect.disabled;
        newCashRegisterWorkerContainer.lastElementChild.toggleAttribute('required');

        if (cashRegisterWorkerSelect.disabled){
            cashRegisterWorkerSelect.selectedIndex = "0"
        }
    }

    form.addEventListener('submit', submit);

    existCashRegisterWorker.addEventListener('change', handleChangeExistWorker);

    let moneyFormat = new Inputmask("(.999){+|1},00", {
        positionCaretOnClick: "radixFocus",
        radixPoint: ",",
        _radixDance: true,
        numericInput: true,
        placeholder: "0",
        numericInput: true,
        definitions: {
            "0": {
                validator: "[0-9\uFF11-\uFF19]"
            }
        },
   })

   inputs.forEach((el) => maskedInputs.push(moneyFormat.mask(el)))
    
}