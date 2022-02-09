import Inputmask from "inputmask";

export default function(){

    let decimalMaskOptions = {
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
    }

    const modalsID = {
        'liquid_money_dollars': [0],
        'liquid_money_dollars_count': 1,
        'liquid_money_bolivares': [0],
        'liquid_money_bolivares_count': 1,
    };

    const denominationModalsID = [
        'liquid_money_dollars_denominations',
        'liquid_money_bolivares_denominations'
    ];

    const getModalsID = (obj) => {
        return Object.keys(obj).reduce((arr, val) => {
            if (!val.includes('_count')){
                arr.push(`${val}`)
            }
            return arr;
        }, [])
    };

    const keypressEventHandler = function(event){
        
        let key = event.key || event.keyCode;

        if (key === 13 || key === 'Enter'){
            event.preventDefault()
            const currency = this.getAttribute('data-currency');
            const tBody = document.querySelector(`#${this.id} tbody`);
            tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.id, currency));
            const input = document.querySelector(`#${this.id}_${getNewInputID(this.id)}`);
            decimalMaskOptions.suffix = currency;
            (new Inputmask(decimalMaskOptions)).mask(input)
            modalsID[`${this.id}_count`]++;
            saveNewInputID(this.id);
        }
    };

    const clickEventHandler = function(event){
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
                        decimalMaskOptions.suffix = this.getAttribute('data-currency');
                        (new Inputmask(decimalMaskOptions)).mask(input);
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

                document.getElementById(`total_${this.id}`).value = total > 0 ? total : 0;
            }
        }
    };

    // --- HANDLING MODAL TO LIQUID MONEY DENOMINATIONS --- //
    const handleClickEventDenominationsModal = function(event){
        const closest = event.target.closest('button');

        if(closest && closest.tagName === 'BUTTON'){

            const idRow = closest.getAttribute('data-del-row');
            const modaToggleID = closest.getAttribute('data-modal-toggle');
            
            if (modaToggleID){ // Checking if it's closing the modal

                // get all inputs of the modal
                let inputs = document.querySelectorAll(`#${this.id} input`)
                const total = Array.from(inputs).reduce((acc, el) => {
                    let denomination = parseFloat(el.getAttribute('data-denomination'));
                    let num = formatAmount(el.value)
                    return acc + (num * denomination);
                }, 0);

                document.getElementById(`total_${this.id}`).value = total > 0 ? total : 0;
            }
        }
    };

    (function(modalState, denominationModalsID){  

        // Get the modals ID
        const modalsID = getModalsID(modalState);

        // Get the total input IDs
        const totalInputsID = modalsID.map(el => `#total_${el}`);

        // Get the total Input Elements
        const totalInputs = document.querySelectorAll(totalInputsID.join(','))

        // Apply the mask to total inputs
        totalInputs.forEach(el => { 
            // Setting up currency suffix for each input
            decimalMaskOptions.suffix = el.getAttribute('data-currency');
            (new Inputmask(decimalMaskOptions)).mask(el)
        })

        // Get Modal Elements
        const modals = document.querySelectorAll(modalsID.map(el => `#${el}`).join(','));
        let currencies = [];

        // Attach events to modals
        modals.forEach(el => {
            el.addEventListener("keypress", keypressEventHandler);
            el.addEventListener("click", clickEventHandler);
            currencies.push(` ${el.getAttribute('data-currency')}`);
        });

        // Get the default input IDs in modals
        const defaultInputsID = modalsID.map(el => `#${el}_0`);

        // Get the default Input Elements in modals
        const defaultInputs = document.querySelectorAll(defaultInputsID.join(','))

        // Apply the mask to default inputs
        defaultInputs.forEach((el, key) => {
            decimalMaskOptions.suffix = currencies[key];
            (new Inputmask(decimalMaskOptions)).mask(el)
        })

        // Apply mask to default total denominations input
        const totalInputsDenominationsID = modalsID.map(el => `#total_${el}_denominations`);
        const totalInputDenominations = document.querySelectorAll(totalInputsDenominationsID.join(','))

        // Apply the mask to total denominations inputs

        totalInputDenominations.forEach(el => { 
            // Setting up currency suffix for each input
            decimalMaskOptions.suffix = el.getAttribute('data-currency');
            (new Inputmask(decimalMaskOptions)).mask(el)
        })

        // Attach event handlers to denominations money modals
        const denominationModals = document
            .querySelectorAll(denominationModalsID
                .map(el => `#${el}`)
                .join(',')
            );
        
        denominationModals.forEach(el => {
            el.addEventListener("click", handleClickEventDenominationsModal);  
        });

    })(modalsID, denominationModalsID);

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

    const inputTemplate = (name, currency) => `
        <input type="text" placeholder="0.00 ${currency}" id="${name}_${getNewInputID(name)}" name="${name}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `
    const tableRowTemplate = (name, currency) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewInputID(name)}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${modalsID[`${name}_count`] + 1}</td>
            <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name, currency) }
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

        // Remove suffix if exists
        if (index !== -1){
            amount = amount.slice(0, index);
        }
        
        // Check if value is zero
        if (amount === defaultValue){
            return 0;
        }
        
        let arr = amount.split(',', 2);
        let integer = arr[0] ?? null;
        let decimal = arr[1] ?? null;
        
        let integerStr = integer.split(".").join();
        
        // Check if it is an integer number
        if (!decimal){
            return parseInt(integerStr);
        }

        let numberString = integerStr + '.' + decimal;

        return (Math.round((parseFloat(numberString) + Number.EPSILON) * 100) / 100)
    }

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