import {decimalInputs} from '_utilities/decimalInput';

import { SIGN as CURRENCY_SYMBOLS_MAP} from '_constants/currencies';

const MoneyRecordTablePrototype = { 
    init(container, tableName, currency){
        this.container = container;
        this.tableName = tableName;
        this.currency = currency;

        if (!container){
            return false;
        }

        return true;
    },
    addRow ({id, total}){
        if (!this.isContainerDefined()){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');

        tBody.insertAdjacentHTML('beforeend', this.getTableRowTemplate(id, total));
        const input = tBody.querySelector(`#${this.tableName}_${id}`);

        decimalInputs[this.currency].mask(input);

        input.focus();
    },
    deleteRow(rowID){
        if (!this.isContainerDefined()){
            return;
        }
        
        const tBody = this.container.querySelector('tbody')
        const row = tBody ? tBody.querySelector(`tr[data-id="${rowID}"]`) : null
        tBody.removeChild(row)
        this.updateTableIDColumn(tBody)
    },
    resetLastInput (id){
        if (!this.isContainerDefined()){
            return;
        }

        const tBody = this.container.querySelector('tbody');
        const input = tBody.querySelector(`#${this.tableName}_${id}`);
        
        if (input && input?.inputmask){
            input.value = 0;
        }
    },
    getInputTemplate(id){
        return `
        <input type="text" placeholder="0.00 ${CURRENCY_SYMBOLS_MAP[this.currency]}" id="${this.tableName}_${id}" name="${this.tableName}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">`
    },
    getTableRowTemplate(id, total){
        return `
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${id}>
                <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${total}</td>
                <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                    ${ this.getInputTemplate(id) }
                </td>
                <td class="py-4 pl-3 pr-6 text-sm text-center font-medium whitespace-nowrap">
                    <button data-modal="remove" data-del-row="${id}" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                        <i class="fas fa-times  text-white"></i>                        
                    </button>
                </td>
            </tr>
        `;
    },
    isContainerDefined(){
        return this.container !== null
    },
    updateTableIDColumn(container){
        const colsID = container.querySelectorAll(`td[data-table="num-col"]`);

        for (let i = 0; i < colsID.length; i++){
            colsID[i].innerHTML = i + 1;
        }
    }
}

const MoneyRecordTable = function(){

}

MoneyRecordTable.prototype = MoneyRecordTablePrototype
MoneyRecordTable.prototype.constructor =MoneyRecordTable; 

export default MoneyRecordTable;