import Inputmask from "inputmask";

export default function(){

    let moneyFormat = new Inputmask({
        alias:'decimal',
        suffix:' $',
        positionCaretOnClick: "radixFocus",
        digits: 2,
        radixPoint: ",",
        _radixDance: true,
        numericInput: true,
        placeholder: "0",
        definitions: {
            "0": {
                validator: "[0-9\uFF11-\uFF19]"
            },
        },
    });

    // --- HANDLING MODAL TO MONEY ENTRANCE ---
    const liquidMoneyDollarsModal = document.getElementById('liquid_money_dollars');

    const modalsID = {
        'liquid_money_dollars': [0],
        'liquid_money_dollars_count': 1
    };

    (function(modalState){  

        // Get the modals ID
        const modalsID = Object.keys(modalState).reduce((arr, val) => {
            if (!val.includes('_count')){
                arr.push(`${val}`)
            }
            return arr;
        }, [])
        
        // Get the total input IDs
        const totalInputsID = modalsID.map(el => `#total_${el}`);

        // Get the total Input Elements
        const totalInputs = document.querySelectorAll(totalInputsID.join(','))

        // Apply the mask to total inputs
        totalInputs.forEach(el => moneyFormat.mask(el))

        // Get the default input IDs
        const defaultInputsID = modalsID.map(el => `#${el}_0`);

        // Get the default Input Elements
        const defaultInputs = document.querySelectorAll(defaultInputsID.join(','))

        // Apply the mask to default inputs
        defaultInputs.forEach(el => moneyFormat.mask(el))
    })(modalsID)

    const getNewInputID = (name) => modalsID[name].length === 0 ? 0 : (modalsID[name][modalsID[name].length - 1] + 1);

    const saveNewInputID = (name) => {
        modalsID[name].push(getNewInputID(name));
    }

    const removeInputID = (name, id) => {
        const index = modalsID[name].findIndex((val) => val == id)
        return index !== -1 ? modalsID[name].slice(index, 1) : -1;
    }

    const updateTableIDColumn = (name) => {
        const colsID = document.querySelectorAll(`#${name} td[data-table="num-col"]`);

        for (let i = 0; i < modalsID[`${name}_count`]; i++){
            colsID[i].innerHTML = i + 1;
        }
    }

    const inputTemplate = (name) => `
        <input type="text" placeholder="0.00 $" id="${name}_${getNewInputID(name)}" name="${name}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `
    const tableRowTemplate = (name) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewInputID(name)}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${modalsID[`${name}_count`] + 1}</td>
            <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name) }
            </td>
            <td data-table="convertion-col" class="py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                0.00 bs.s
            </td>
            <td class="py-4 pr-6 text-sm text-center font-medium whitespace-nowrap">
                <button data-del-row="${getNewInputID(name)}" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    const formatAmount = (amount, defaultValue = '0.00') => {

        
        if (!amount){
            return 0;
        }

        let index = amount.indexOf(" ");

        if (index !== -1){
            amount = amount.slice(0, index);
        }
        
        if (amount === defaultValue){
            return 0;
        }
        
        let arr = amount.split(',', 2);
        let integer = arr[0] ?? null;
        let decimal = arr[1] ?? null;
        
        let integerStr = integer.split(".").join();
        
        let numberString = integerStr  + '.' + decimal;

        return (Math.round((parseFloat(numberString) + Number.EPSILON) * 100) / 100)
    }

    liquidMoneyDollarsModal.addEventListener("keypress", function(event){
        
        let key = event.key || event.keyCode;

        if (key === 13 || key === 'Enter'){
            event.preventDefault()
            const tBody = document.querySelector(`#${this.id} tbody`);
            tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.id));
            const input = document.querySelector(`#${this.id}_${getNewInputID(this.id)}`);
            moneyFormat.mask(input);
            modalsID[`${this.id}_count`]++;
            saveNewInputID(this.id);
        }
    })

    liquidMoneyDollarsModal.addEventListener("click", function(event){
        const closest = event.target.closest('button');

        if(closest && closest.tagName === 'BUTTON'){

            const idRow = closest.getAttribute('data-del-row');
            const modaToggleID = closest.getAttribute('data-modal-toggle');
            
            if (idRow){ // Checking if it's Deleting a row
                let parent = document.querySelector(`#${this.id} tbody`)

                if(parent.children.length === 1){
                    const input = document.getElementById(`${this.id}_${idRow}`);
                    if (input?.inputmask){
                        input.value = 0;
                        input.inputmask.remove();
                        moneyFormat.mask(input)
                    }
                } else {
                    let child = document.querySelector(`#${this.id} tr[data-id="${idRow}"]`)
                    parent.removeChild(child);
                    modalsID[`${this.id}_count`]--;
                    removeInputID(this.id, idRow)
                    updateTableIDColumn(this.id);
                }

            } else if (modaToggleID){ // Checking if it's closing the modal

                // get all inputs of the modal
                let inputs = document.querySelectorAll(`#${this.id} input`)
                const total = Array.from(inputs).reduce((acc, el) => {
                    let num = formatAmount(el.value)
                    return acc + num;
                }, 0);

                console.log(total);

                document.getElementById(`total_${this.id}`).value = total > 0 ? total : 0;
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

}