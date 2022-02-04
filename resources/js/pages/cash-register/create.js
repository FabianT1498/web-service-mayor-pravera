import Inputmask from "inputmask";

export default function(){
    
    // --- HANDLING MODAL TO MONEY ENTRANCE ---
    const liquidMoneyDollarsModal = document.getElementById('liquid_money_dollars');

    const inputsID = {
        'liquid_money_dollars': [0],
        'liquid_money_dollars_count': 1
    }

    const getNewInputID = (name) => inputsID[name].length === 0 ? 0 : (inputsID[name][inputsID[name].length - 1] + 1);

    const saveNewInputID = (name) => {
        inputsID[name].push(getNewInputID(name));
    }

    const removeInputID = (name, id) => {
        const index = inputsID[name].findIndex((val) => val == id)
        return index !== -1 ? inputsID[name].slice(index, 1) : -1;
    }

    const updateTableIDColumn = (name) => {
        const colsID = document.querySelectorAll(`#${name} td[data-table="num-col"]`);

        for (let i = 0; i < inputsID[`${name}_count`]; i++){
            colsID[i].innerHTML = i + 1;
        }
    }

    const inputTemplate = (name) => `
        <input type="text" id="${name}_${getNewInputID(name)}" name="${name}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `
    const tableRowTemplate = (name) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewInputID(name)}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">${inputsID[`${name}_count`] + 1}</td>
            <td class="py-4 pl-3 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name) }
            </td>
            <td data-table="convertion-col" class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                0.00 bs.s
            </td>
            <td class="py-4 pr-6 text-sm font-medium text-right whitespace-nowrap">
                <button data-del-row="${getNewInputID(name)}" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times font-bold text-md text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    liquidMoneyDollarsModal.addEventListener("keypress", function(event){
        
        let key = event.key || event.keyCode;

        if (key === 13 || key === 'Enter'){
            event.preventDefault()
            const tBody = document.querySelector(`#${this.id} tbody`);
            tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.id));
            const input = document.querySelector(`#${this.id}_${getNewInputID(this.id)}`);
            moneyFormat.mask(input);
            inputsID[`${this.id}_count`]++;
            saveNewInputID(this.id);
        }
    })

    liquidMoneyDollarsModal.addEventListener("click", function(event){
        const closest = event.target.closest('button');

        if(closest && closest.tagName === 'BUTTON'){

            const id = closest.getAttribute('data-del-row');
            let parent = document.querySelector(`#${this.id} tbody`)

            if(parent.children.length === 1){
                document.getElementById(`${this.id}_${id}`).value = 0;
            } else {
                let child = document.querySelector(`#${this.id} tr[data-id="${id}"]`)
                parent.removeChild(child);
                inputsID[`${this.id}_count`]--;
                removeInputID(this.id, id)
                updateTableIDColumn(this.id);
            }
        }
    })

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

        console.log(event)
       
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
}