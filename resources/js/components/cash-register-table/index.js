import PubSub from "pubsub-js";

import { formatAmount } from '_utilities/mathUtilities'

import DecimalInput from '_components/decimal-input';

import { SIGN as CURRENCY_SYMBOLS_MAP, CURRENCIES} from '_assets/currencies';

const CashRegisterTable = function(tableName, currency, method){

    let rowsCount = 1;
    let idsList = [0];
    this.tableName = tableName || "";
    this.currency = currency || CURRENCIES.DOLLAR;
    this.method = method || "cash"

    this.init = (container) => {
        this.container = container;

        if (PubSub.getSubscriptions('attachMask').length === 0){
            let decimalInputDollar = new DecimalInput();
            decimalInputDollar.init();
        }

        console.log(`getTotal.records.${this.method}.${this.currency}`);

        PubSub.subscribe(`addRow.${this.method}.${this.currency}`, addRow);
        PubSub.subscribe(`deleteRow.${this.method}.${this.currency}`, deleteRow);        
        PubSub.subscribe(`getTotal.records.${this.method}.${this.currency}`, getTotal);

        if (currency !== CURRENCIES.BOLIVAR){
            PubSub.subscribe(`updateConvertionCol.${this.method}.${this.currency}`, updateConvertionCol);  
        }

        setInitialMask()
    }

    const setInitialMask = () => {
        if (!this.container){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');
        if (tBody && tBody.children.length === 1){
            let input = tBody.querySelector('input');
            PubSub.publish('attachMask', {input, currency: this.currency});
        }
    }

    const getTotal = (msg, data) => {
        if (!this.container){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');
        let inputs = tBody.querySelectorAll(`input`)
        const total = Array.from(inputs).reduce((acc, el) => {
            let num = formatAmount(el.value)
            return acc + num;
        }, 0);

        document.getElementById(`total_${this.tableName}`).value = total > 0 ? total : 0;
    }

    const addRow = (msg, data) => {
        if (!this.container){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');

        tBody.insertAdjacentHTML('beforeend', tableRowTemplate(this.tableName, this.currency));
        const input = tBody.querySelector(`#${this.tableName}_${getNewID()}`);

        PubSub.publish('attachMask', {input, currency: this.currency});

        rowsCount++;
        saveNewID();
    }

    const deleteRow = (msg, data) => {
        if (!this.container){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');
        const row = data.row;
        const rowID = data.rowID;

        if (tBody.children.length === 1){
            const input = row.querySelector(`#${this.tableName}_${rowID}`);
            if (input && input?.inputmask){
                input.value = 0;

                // Update convertion col only in foreign currency tables
                const convertionCol = row.querySelector('td[data-table="convertion-col"]');

                if (convertionCol){
                    convertionCol.innerHTML = '0.00' . CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]
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
        let dollarExchangeBs = data.lastDollarExchangeVal;
        let amount = data.amount;

        let columnData = rowElement.querySelector('td[data-table="convertion-col"]');

        if (columnData){
            columnData.innerHTML = `${ (Math.round(((dollarExchangeBs * amount) + Number.EPSILON) * 100) / 100) } ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}`;
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
        <input type="text" placeholder="0.00 ${CURRENCY_SYMBOLS_MAP[currency]}" id="${name}_${getNewID()}" name="${name}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `
    const tableRowTemplate = (name, currency) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${getNewID()}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${rowsCount + 1}</td>
            <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(name, currency) }
            </td>
            ${ currency !== 'bs' 
                ?  `<td data-table="convertion-col" class="py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    0.00 ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}
                    </td>`
                : ''
            }
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