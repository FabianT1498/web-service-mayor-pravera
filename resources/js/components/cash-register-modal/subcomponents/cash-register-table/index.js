import PubSub from "pubsub-js";

import DecimalInput from '_components/cash-register-modal/decimal-input';

import { formatAmount } from '_utilities/mathUtilities'


const CashRegisterTable = function(tableName){

    let rowsCount = 1;
    let idsList = [0];
    let tableName = tableName;

    this.init = (container) => {
        this.container = container;
        let decimalInputSubs = new DecimalInput();

        decimalInputSubs.init();

        PubSub.subscribe('addRow', addRow);
        PubSub.subscribe('deleteRow', deleteRow);        
        PubSub.subscribe('updateConvertionCol', updateConvertionCol);  
    }

    const addRow = (msg, data) => {
        if (!this.container){
            return;
        }
        
        const currency = data.currency;
        const tBody = container.querySelector('tbody');

        tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.tableName, currency));
        const input = document.querySelector(`#${this.tableName}_${getNewInputID()}`);

        PubSub.publish('attachMask', {input, currency});

        modalsID[`${this.id}_count`]++;
        saveNewInputID(this.id);
    }

    const inputTemplate = (name, currency) => `
        <input type="text" placeholder="0.00 ${currency}" id="${name}_${getNewInputID()}" name="${name}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `
    const tableRowTemplate = (name, currency) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewInputID()}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${this.rowsCount + 1}</td>
            <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name, currency) }
            </td>
            <td data-table="convertion-col" class="py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                0.00 bs.s
            </td>
            <td class="py-4 pr-6 text-sm text-center font-medium whitespace-nowrap">
                <button data-del-row="${getNewInputID()}" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    const getNewInputID = () => this.idsList.length === 0 ? 0 : (this.idsList[idsList.length - 1] + 1);

    const saveNewInputID = () => {
        this.idsList.push(getNewInputID());
    }

    const removeInputID = (name, id) => {
        const index = modalsID[name].findIndex((val) => val == id)
        return index !== -1 ? modalsID[name].slice(index, 1) : -1;
    }

    const updateConvertionCol = function(msg, data){
        let rowElement = data.row;
        let dollarExchangeBs = data.dollarExchangeBs;
        let amount = data.amount;

        let columnData = rowElement.querySelector('td[data-table="convertion-col"]');

        if (columnData){
            const value = formatAmount(amount);
            columnData.innerHTML = `${ (Math.round(((dollarExchangeBs * value) + Number.EPSILON) * 100) / 100) } Bs.s`;
        }
    };
}

export default CashRegisterTable;