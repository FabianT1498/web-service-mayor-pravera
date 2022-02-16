import PubSub from "pubsub-js";

import DecimalInput from '_components/decimal-input';

const CashRegisterTable = function(tableName){

    let rowsCount = 1;
    let idsList = [0];
    this.tableName = tableName || "";

    this.init = (container) => {
        this.container = container;
        let decimalInput = new DecimalInput();

        decimalInput.init();

        PubSub.subscribe('addRow', addRow);
        PubSub.subscribe('deleteRow', deleteRow);        
        PubSub.subscribe('updateConvertionCol', updateConvertionCol);  
    }

    const addRow = (msg, data) => {
        if (!this.container){
            return;
        }
        
        const currency = data.currency;
        const tBody = this.container.querySelector(tbody);

        tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.tableName, currency));
        const input = document.querySelector(`#${this.tableName}_${getNewID()}`);

        PubSub.publish('attachMask', {input, currency});

        rowsCount++;
        saveNewID();
    }

    const deleteRow = (msg, data) => {
        if (!this.container){
            return;
        }
        
        const tBody = this.container.querySelector(tbody);
        const row = data.row;
        const rowID = data.rowID;

        if (tBody.children.length === 1){
            const input = row.querySelector(`#${this.tableName}_${rowID}`);
            if (input && input?.inputmask){
                input.value = 0;

                // Update convertion col only in foreign currency tables
                const convertionCol = row.querySelector('td[data-table="convertion-col"]');

                if (convertionCol){
                    convertionCol.innerHTML = '0.00 Bs.s'
                }
            }
        } else {
            tBody.removeChild(row);
            rowsCount--;
            removeID(rowID)
            updateTableIDColumn();
        }
    }

    const updateConvertionCol = function(msg, data){
        let rowElement = data.row;
        let dollarExchangeBs = data.dollarExchangeBs;
        let amount = data.amount;

        let columnData = rowElement.querySelector('td[data-table="convertion-col"]');

        if (columnData){
            columnData.innerHTML = `${ (Math.round(((dollarExchangeBs * amount) + Number.EPSILON) * 100) / 100) } Bs.s`;
        }
    };

    const updateTableIDColumn = () => {
        if (!this.container){
            return;
        }
        const tBody = this.container.querySelector('tbody');
        const colsID = tBody.querySelectorAll(`td[data-table="num-col"]`);

        for (let i = 0; i < rowsCount; i++){
            colsID[i].innerHTML = i + 1;
        }
    }

    const inputTemplate = (name, currency) => `
        <input type="text" placeholder="0.00 ${currency}" id="${name}_${getNewID()}" name="${name}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `
    const tableRowTemplate = (name, currency) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewID()}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${rowsCount + 1}</td>
            <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name, currency) }
            </td>
            <td data-table="convertion-col" class="py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                0.00 bs.s
            </td>
            <td class="py-4 pr-6 text-sm text-center font-medium whitespace-nowrap">
                <button data-del-row="${getNewID()}" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    const getNewID = () => idsList.length === 0 ? 0 : (idsList[idsList.length - 1] + 1);

    const saveNewID = () => {
        idsList.push(getNewID());
    }

    const removeID = (id) => {
        const index = idsList.findIndex((val) => val == id)
        return index !== -1 ? idsList.slice(index, 1) : -1;
    }

    
}

export default CashRegisterTable;