import Inputmask from "inputmask";
import { reduce } from "lodash";

export default function(){
    let inputs = document.querySelectorAll('[data-currency^="amount"]');
    const existCashRegisterWorker = document.getElementById('exist_cash_register_worker');
    const cashRegisterWorkerSelect = document.getElementById('cash_register_worker');
    const newCashRegisterWorkerContainer = document.getElementById('hidden-new-cash-register-worker-container');

    // Modal containers
    const exampleModal = document.getElementById('authentication-modal');

    exampleModal.addEventListener("keyup", function(event){
        
        let key = event.key;
 
        if (key === "Enter"){
            event.preventDefault();
            console.log('esto se disparo')
            const newRow = `
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">1</td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">
                        <x-input 
                            id="new_cash_register_worker" 
                            placeholder="Nombre del cajero"
                            class="w-full"
                            type="text" 
                            name="new_cash_register_worker" 
                            :value="old('new_cash_register_worker') ? old('new_cash_register_worker') : ''" 
                        />
                    </td>
                    <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        12,000 Bs.F
                    </td>
                    <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
                        <button  disabled class="bg-stone-300 flex justify-center w-8 h-8 items-center transition-colors duration-150 rounded-full shadow-lg">
                            <i class="fas fa-times text-md text-red-600"></i>                        
                        </button>
                    </td>
                </tr>
            `;

            console.log(this);
        }
    })

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