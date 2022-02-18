import PubSub from "pubsub-js";

import BankCollection from '_app/collections/bankCollection';
import CURRENCY_SYMBOLS_MAP from '_assets/currencies';
import { getAllBanks } from '_services/banks'

const SalePointTable = function(name, currency){

    this.name = name || "";
    this.currency = currency || "dollar";

    let rowsCount = 0;
    let idsList = [];
    let oldValueSelects = {};
    
    this.init = (container) => {
        this.container = container;
        this.banks = new BankCollection();

        fetchInitialData().then(res => {
            this.banks.setElements(res.banks);
        }).catch(err => {
            console.log(err)
        });

        PubSub.subscribe('addRow.salePoint', addRow);
        PubSub.subscribe('deleteRow.salePoint', deleteRow);
        PubSub.subscribe('changeSelect.salePoint', changeSelect)
    }

    const fetchInitialData = async function(){
        try {
            const banks = await getAllBanks();
            return {banks}
        } catch(e){
            return { banks: [] }
        }
    }

    const addRow = (msg, data) => {
    
        if (this.banks.getLength() === 0 || !this.container){
            return;
        }

        const tBody = this.container.querySelector(`tbody`);
        tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.name, this.currency));

        const input_debit = tBody.querySelector(`#${this.name}_debit_${getNewID()}`);
        const input_credit = tBody.querySelector(`#${this.name}_credit_${getNewID()}`);

        PubSub.publish('attachMask', {input: input_debit, currency: this.currency});
        PubSub.publish('attachMask', {input: input_credit, currency: this.currency});

        rowsCount++;
        
        let rowsIDS = Object.keys(oldValueSelects);
        
        if (rowsIDS.length > 0){
            oldValueSelects[getNewID()] = this.banks.shiftElement();
            const selectors = getBankSelectSelectors(rowsIDS);
            updateBankSelects(tBody, selectors);           
        } else {
            oldValueSelects[getNewID()] = this.banks.shiftElement();
        }
        
        saveNewID();
    }

    const deleteRow = (msg, {row, rowID}) => {
        if (!this.container){
            return false;
        }

        /**
         * Eliminar un pv
         * 1. Obtener fila y su id
         * 2. Buscar en los valores antiguos el banco
         * 3. Remover el id de los valores antiguos
         * 4. Agregar banco antiguo a la coleccion
         * 5. Eliminar fila de la tabla
         * 6. Actualizar los selects restantes
         */

        if (!rowID || !row){
            return false;
        }

        if (oldValueSelects[rowID] === undefined){
            return false;
        }

        let bank = oldValueSelects[rowID]

        delete oldValueSelects[rowID]

        this.banks.pushElement(bank);

        const tBody = this.container.querySelector('tbody');

        tBody.removeChild(row);

        let rowsIDS = Object.keys(oldValueSelects);
        
        rowsCount--;

        if (rowsIDS.length > 0){
            const selectors = getBankSelectSelectors(rowsIDS);
            updateBankSelects(tBody, selectors);
        }
    }

    const changeSelect = (msg, data) => {

        if (this.banks.getLength() === 0 || !this.container){
            return;
        }
        

        let rowID = data.rowID;
        let newValue = data.newSelectValue;
        
        if (!rowID){
            return false;
        }

        if (oldValueSelects[rowID] === undefined){
            return false;
        }

        const tBody = this.container.querySelector(`tbody`);

        // Old value is pushed again in collection
        this.banks.pushElement(oldValueSelects[rowID]);
            
        // Remove the new value from available banks
        this.banks.deleteElementByName(newValue);

        // Set the new value in old value select
        oldValueSelects[rowID] = newValue;

        let rowsIDS = Object.keys(oldValueSelects);

        const selectors = getBankSelectSelectors(rowsIDS);
        updateBankSelects(tBody, selectors);
    }

    const inputTemplate = (name, currency, type) => `
        <input type="text" placeholder="0.00 ${CURRENCY_SYMBOLS_MAP[currency]}" id="${name}_${type}_${getNewID()}" name="${name}_${type}[]" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `

    const tableRowTemplate = (name, currency = 'bs') => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewID()}>
            <td data-table="num-col" class="py-4 pl-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${rowsCount + 1}</td>
            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                <select class="w-full form-select" name="point_sale_bs_bank[]">
                    ${this.banks.getAll().map(el => `<option value="${el}">${el}</option>`).join('')}
                </select>
            </td>
            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name, currency, 'debit') }
            </td>
            <td data-table="convertion-col" class="pl-3 py-4 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name, currency, 'credit') }
            </td>
            <td class="py-4 pl-3 text-sm text-center font-medium whitespace-nowrap">
                <button data-modal="delete" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    const getBankSelectSelectors = (rowsIDS) => {

        if (!rowsIDS){ return ''};

        if (rowsIDS.length === 0){ return ''};

        return rowsIDS.map((el) => `tr[data-id="${el}"] select`).join(',');
    };

    const updateBankSelects = (container, selectors) => {
        if (selectors === ''){ return false };

        const selectSelectorsElems = container.querySelectorAll(selectors);

        selectSelectorsElems.forEach(function(el) {
            let options = [el.value, ...this.banks.getAll()];
            const html = options.map(el => `<option value="${el}">${el}</option>`).join('');
            el.innerHTML = html;
        }, this);

        return true;
    }

    const getNewID = () => idsList.length === 0 ? 0 : (idsList[idsList.length - 1] + 1);

    const saveNewID = () => {
        idsList.push(getNewID());
    }

    const removeID = (id) => {
        const index = idsList.findIndex((val) => val == id)
        return index !== -1 ? idsList.slice(index, 1) : -1;
    }    
}

export default SalePointTable;