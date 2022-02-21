import { formatAmount } from '_utilities/mathUtilities'

import {decimalInputs} from '_components/decimal-input';

import { SIGN as CURRENCY_SYMBOLS_MAP} from '_constants/currencies';

const CashRegisterTable = function(tableName, currency){

    this.tableName = tableName || "";
    this.currency = currency;

    this.init = (container) => {
        this.container = container;

        // if (PubSub.getSubscriptions('attachMask').length === 0){
        //     let decimalInputDollar = new DecimalInput();
        //     decimalInputDollar.init();
        // }
        setInitialMask()
    }

    this.addRow = ({id, total}) => {
        if (!isContainerDefined()){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');

        tBody.insertAdjacentHTML('beforeend', tableRowTemplate(id, total));
        const input = tBody.querySelector(`#${this.tableName}_${id}`);

        decimalInputs[this.currency].mask(input);
    }

    this.deleteRow = (rowID) => {
        if (!isContainerDefined()){
            return;
        }
        
        const tBody = this.container.querySelector('tbody')
        const row = tBody ? tBody.querySelector(`tr[data-id="${rowID}"]`) : null
        tBody.removeChild(row)
        updateTableIDColumn(tBody)
    }

    this.resetLastInput = function(id){
        if (!isContainerDefined()){
            return;
        }

        const tBody = this.container.querySelector('tbody');
        const input = tBody.querySelector(`#${this.tableName}_${id}`);
        
        if (input && input?.inputmask){
            input.value = 0;

            // // Update convertion col only in foreign currency tables
            // const convertionCol = row.querySelector('td[data-table="convertion-col"]');

            // if (convertionCol){
            //     convertionCol.innerHTML = '0.00' . CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]
            // }
        }
    }

    const setInitialMask = () => {
        if (!isContainerDefined()){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');
        let input = tBody.querySelector('input');
        decimalInputs[this.currency].mask(input);
    }

    const updateTableIDColumn = (container) => {
        const colsID = container.querySelectorAll(`td[data-table="num-col"]`);

        for (let i = 0; i < colsID.length; i++){
            colsID[i].innerHTML = i + 1;
        }
    }

    const inputTemplate = (id) => `
        <input type="text" placeholder="0.00 ${CURRENCY_SYMBOLS_MAP[this.currency]}" id="${this.tableName}_${id}" name="${this.tableName}[]" class="w-36 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `
    const tableRowTemplate = (id, total) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${id}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${total}</td>
            <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ inputTemplate(id) }
            </td>
            <td class="py-4 pr-6 text-sm text-center font-medium whitespace-nowrap">
                <button data-del-row="${id}" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    const isContainerDefined = () => {
        return this.container !== null
    }

    //  ${ currency !== 'bs' 
    //             ?  `<td data-table="convertion-col" class="py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
    //                 0.00 ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}
    //                 </td>`
    //             : ''
    // }

    // const getTotal = (msg, data) => {
    //     if (!this.container){
    //         return;
    //     }
        
    //     const tBody = this.container.querySelector('tbody');
    //     let inputs = tBody.querySelectorAll(`input`)
    //     const total = Array.from(inputs).reduce((acc, el) => {
    //         let num = formatAmount(el.value)
    //         return acc + num;
    //     }, 0);

    //     document.getElementById(`total_${this.tableName}`).value = total > 0 ? total : 0;
    // }

    

    // const updateConvertionCol = function(msg, data){
    //     let rowElement = data.row;
    //     let dollarExchangeBs = data.lastDollarExchangeVal;
    //     let amount = data.amount;

    //     let columnData = rowElement.querySelector('td[data-table="convertion-col"]');

    //     if (columnData){
    //         columnData.innerHTML = `${ (Math.round(((dollarExchangeBs * amount) + Number.EPSILON) * 100) / 100) } ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}`;
    //     }
    // };

    
}

export default CashRegisterTable;