import MoneyRecordTable from './MoneyRecordTable';

import { SIGN as CURRENCY_SYMBOLS_MAP, CURRENCIES} from '_constants/currencies';

const ForeignMoneyRecordTable = function(tableName, currency){
    MoneyRecordTable.call(this, tableName, currency)

    this.resetLastInput = function(id){
        MoneyRecordTable.prototype.resetLastInput.call(this, id)

        if (!this.isContainerDefined()){
            return;
        }
        
        const row = this.container.querySelector(`tr[data-id="${id}"]`);
        const convertionCol = row.querySelector('td[data-table="convertion-col"]');
    
        if (convertionCol){
            convertionCol.innerHTML = `0.00 ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}`
        }
    }

    this.getTableRowTemplate = (id, total) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${id}>
            <td data-table="num-col" class="py-4 pl-6 pr-3 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${total}</td>
            <td class="py-4 pl-3 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ this.getInputTemplate(id) }
            </td>
            <td data-table="convertion-col" class="py-4 px-6 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                0.00 ${CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR]}
            </td>
            <td class="py-4 pr-6 text-sm text-center font-medium whitespace-nowrap">
                <button data-del-row="${id}" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    this.updateConvertionCol = function({rowID, formatedConvertion}){
        const row = this.container.querySelector(`tr[data-id="${rowID}"]`);
        let columnData = row.querySelector('td[data-table="convertion-col"]');
        columnData.innerHTML = formatedConvertion
    };


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
}

ForeignMoneyRecordTable.prototype = Object.create(MoneyRecordTable.prototype)
ForeignMoneyRecordTable.prototype.constructor = ForeignMoneyRecordTable;

export default ForeignMoneyRecordTable;