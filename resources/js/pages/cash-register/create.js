import Inputmask from "inputmask";

import { getAllBanks } from './../../services/banks';

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
        'point_sale_bs': [0],
        'point_sale_bs_count': 0
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

    // --- HANDLING LIST ENTRANCE MODAL ---
    const keyDownEventHandler = function(event){
        let key = event.key || event.keyCode;

        if (key === 8 || key === 'Backspace'){ // Handle case to convert dollar to Bs.S
            updateConvertionCol(event)
        }
    };

    const keypressEventHandler = function(event){
        
        event.preventDefault();

        let key = event.key || event.keyCode;

        if (isFinite(key)){ // Handle case to convert dollar to Bs.S
            updateConvertionCol(event)
        }
        else if (key === 13 || key === 'Enter'){ // Handle new table's row creation
            
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
                        const convertionCol = closest?.closest('tr')?.children[2];
                        const dataConvertionCol = convertionCol ? convertionCol?.getAttribute('data-table') : null;
                        if (dataConvertionCol && dataConvertionCol === 'convertion-col'){
                            convertionCol.innerHTML = `0.00 Bs.s`
                        }
                        // input.inputmask.remove();
                        // decimalMaskOptions.suffix = this.getAttribute('data-currency');
                        // (new Inputmask(decimalMaskOptions)).mask(input);
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

    const updateConvertionCol = function(event){

        const convertionCol = event.target.closest('tr').children[2];
        const dataConvertionCol = convertionCol ? convertionCol?.getAttribute('data-table') : null;

        if (dataConvertionCol && dataConvertionCol === 'convertion-col'){
            const dollarExchangeBs = parseFloat(document.querySelector(`#last-dollar-exchange-bs-val`).value);
            const value = formatAmount(event.target.value);
            convertionCol.innerHTML = `${ (Math.round(((dollarExchangeBs * value) + Number.EPSILON) * 100) / 100) } Bs.s`;
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

    // --- HANDLING POINT SALE MODAL --- //
    const oldValuesSelects = {};

    const handleClickEventPointSaleModal = function(event){
        const closest = event.target.closest('button');

        if(closest && closest.tagName === 'BUTTON'){
            
            const action = closest.getAttribute('data-modal')

            if (!action){
                return;
            }

            if (action === 'add'){
                
                if (banks.getBanks().length === 0){
                    return;
                }

                const tBody = document.querySelector(`#${this.id} tbody`);
                tBody.insertAdjacentHTML('beforeend', pointSaletableRowTemplate(this.id));

                const input_debit = document.querySelector(`#${this.id}_debit_${getNewInputID(this.id)}`);
                const input_credit = document.querySelector(`#${this.id}_credit_${getNewInputID(this.id)}`);

                decimalMaskOptions.suffix = ' Bs.S';
                const inputMask = new Inputmask(decimalMaskOptions);
                inputMask.mask([input_debit,input_credit]);
                modalsID[`${this.id}_count`]++;

                oldValuesSelects[getNewInputID(this.id)] = banks.shiftBank();
                let rowsIDS = Object.keys(oldValuesSelects);
                
                if (rowsIDS.length > 1){
                    const selectors = getBankSelectSelectors(this.id, rowsIDS, getNewInputID(this.id));
                    updateBankSelects(selectors);           
                }

                saveNewInputID(this.id);
                
            } else if (action === 'remove'){
                // Logic is here
            }
        }
    };

    const getBankSelectSelectors = (parentID, rowsIDS, currentID = null) => {

        if (!rowsIDS){ return ''};

        if (rowsIDS.length === 0){ return ''};

        let selectSelectors = '';

        if (!currentID){
            selectSelectors = rowsIDS.map((el) => `#${parentID} tr[data-id="${el}"] select`).join(',');
        } else {
            selectSelectors= rowsIDS.reduce((prev, val) => {
            
                if (val !== currentID){
                    prev.push(`#${parentID} tr[data-id="${val}"] select`);
                }

                return prev;
            }, []).join(',');
        }

        return selectSelectors;
        
    };

    const updateBankSelects = (selectors) => {
        if (selectors === ''){ return false };

        const selectSelectorsElems = document.querySelectorAll(selectors);

        selectSelectorsElems.forEach(el => {
            let options = [el.value, ...banks.getBanks()];
            const html = options.map(el => `<option value="${el}">${el}</option>`).join('');
            el.innerHTML = html;
        });

        return true;
    }

    const handleOnChangeEventPointSaleModal = function(event){

        let newValue = '';

        // get the select's row ID
        let row = event.target.closest('tr');

        if (row && row.getAttribute('data-id')){

            let rowID = row.getAttribute('data-id');

            // Old value is pushed again in arr
            banks.pushBank(oldValuesSelects[rowID]);
            
            // Get the current index
            let index = event.target.selectedIndex;
            
            // Get the new value
            newValue = event.target.options[index].value;

            // Remove the new value from available banks
            banks.deleteBank(newValue);

            // Set the new value in old value select
            oldValuesSelects[rowID] = newValue;

            let rowsIDS = Object.keys(oldValuesSelects);

            if (rowsIDS.length > 1){
                const selectors = getBankSelectSelectors(this.id, rowsIDS);
                updateBankSelects(selectors);
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

            el.addEventListener("keydown", keyDownEventHandler);
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

        // Get the IDs from total denomination inputs
        const totalInputsDenominationsID = modalsID.map(el => `#total_${el}_denominations`);
        
        // Get the total denomination Input Elements
        const totalInputDenominations = document.querySelectorAll(totalInputsDenominationsID.join(','))
        
        // Apply mask to default total denominations input
        totalInputDenominations.forEach(el => { 
            // Setting up currency suffix for each input
            decimalMaskOptions.suffix = el.getAttribute('data-currency');
            (new Inputmask(decimalMaskOptions)).mask(el)
        })

        // Get the denomination Modals Elements
        const denominationModals = document
            .querySelectorAll(denominationModalsID
                .map(el => `#${el}`)
                .join(',')
            );
        
        // Attach event handlers to denominations money modals
        denominationModals.forEach(el => {
            el.addEventListener("click", handleClickEventDenominationsModal);  
        });

        // get Point Sale Bs Modal
        document.querySelector('#point_sale_bs').addEventListener('click', handleClickEventPointSaleModal);
        document.querySelector('#point_sale_bs').addEventListener('change', handleOnChangeEventPointSaleModal);

    })(modalsID, denominationModalsID);

    let banks = (function() {
        let banks = [];

        getAllBanks()
            .then((res) => banks = res.data.data)
            .catch((e) => console.log(e));

        const getBanks = function () {
            return banks;
        }

        const getBank = (index) => {
            return banks[index];
        }

        const deleteBank = (name) => {
            let index = banks.findIndex((val) => val === name);
            if (index !== -1) {
                banks.splice(index, 1);
            }
        }

        const pushBank = (name) => {
            banks.push(name);
            return banks;
        }

        const shiftBank = () => {
            return banks.shift();
        }


        return {
            getBanks,
            getBank,
            deleteBank,
            pushBank,
            shiftBank,
        }
    })();

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
                
        // Check if it is an integer number
        if (!decimal){
            return parseInt(integer);
        }

        let numberString = integer + '.' + decimal;

        return (Math.round((parseFloat(numberString) + Number.EPSILON) * 100) / 100)
    }

    // --- HANDLING POINT SALE
    const pointSaleinputTemplate = (name, currency, type) => `
        <input type="text" placeholder="0.00 ${currency}" id="${name}_${type}_${getNewInputID(name)}" name="${name}_${type}[]" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `

    const pointSaletableRowTemplate = (name, currency = 'Bs.S') => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewInputID(name)}>
            <td data-table="num-col" class="py-4 pl-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${modalsID[`${name}_count`] + 1}</td>
            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                <select class="w-full form-select" name="point_sale_bs_bank[]">
                    ${banks.getBanks().map(el => `<option value="${el}">${el}</option>`).join('')}
                </select>
            </td>
            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ pointSaleinputTemplate(name, currency, 'debit') }
            </td>
            <td data-table="convertion-col" class="pl-3 py-4 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${ pointSaleinputTemplate(name, currency, 'credit') }
            </td>
            <td class="py-4 pl-3 text-sm text-center font-medium whitespace-nowrap">
                <button data-modal="delete" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

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