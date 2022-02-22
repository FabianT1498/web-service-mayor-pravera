import { SIGN as CURRENCY_SYMBOLS_MAP, CURRENCIES} from '_constants/currencies';

import {decimalInputs} from '_utilities/decimalInput';

const SalePointTable = function(){
    
    this.init = (container, name, currency) => {
        this.container = container;
        this.name = name || "";
        this.currency = currency || CURRENCIES.DOLLAR;
    }

    this.addRow = ({prevIDArr, newID, availableBanks, currentBank, totalElements}) => {
    
        if (!this.isContainerDefined()){
            return;
        }

        const tBody = this.container.querySelector(`tbody`);
        tBody.insertAdjacentHTML('beforeend', this.getTableRowTemplate(newID, availableBanks, currentBank, totalElements));

        const input_debit = tBody.querySelector(`#${this.name}_debit_${newID}`);
        const input_credit = tBody.querySelector(`#${this.name}_credit_${newID}`);

        decimalInputs[this.currency].mask(input_debit);
        decimalInputs[this.currency].mask(input_credit);


        if (prevIDArr.length > 0){
            let selectors = getBankSelectSelectors(prevIDArr);
            updateBankSelects(tBody, availableBanks, selectors);           
        }
    }

    this.deleteRow = ({prevIDArr, deleteID, availableBanks, totalElements}) => {
        if (!this.isContainerDefined()){
            return;
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

        const tBody = this.container.querySelector('tbody');
        let row = tBody.querySelector(`tr[data-id="${deleteID}"]`)
        tBody.removeChild(row);

        if (prevIDArr.length > 0){
            const selectors = getBankSelectSelectors(prevIDArr);
            updateBankSelects(tBody, availableBanks, selectors);
            updateTableIDColumn(tBody);
        }
    }

    this.changeSelect = ({prevIDArr, availableBanks }) => {

        if (!this.isContainerDefined()){
            return;
        }

        const tBody = this.container.querySelector('tbody');

        if (prevIDArr.length > 1){
            let selectors = getBankSelectSelectors(prevIDArr);
            updateBankSelects(tBody, availableBanks, selectors);
        }
    }

    this.getInputTemplate = (id, type) => `
        <input type="text" placeholder="0.00 ${CURRENCY_SYMBOLS_MAP[this.currency]}" id="${this.name}_${type}_${id}" name="${this.name}_${type}[]" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    `

    this.getTableRowTemplate = (id, availableBanks, currentBank, total) => `
        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700" data-id=${id}>
            <td data-table="num-col" class="py-4 pl-6 text-sm font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">${total}</td>
            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                <select class="w-full form-select" name="point_sale_bs_bank[]">
                    <option value="${currentBank}">${currentBank}</option>
                    ${availableBanks.map(el => `<option value="${el}">${el}</option>`).join('')}
                </select>
            </td>
            <td class="pl-3 py-4 text-sm text-center font-medium text-gray-500 whitespace-nowrap dark:text-white">
                ${ this.getInputTemplate(id, 'debit') }
            </td>
            <td data-table="convertion-col" class="pl-3 py-4 text-sm text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${ this.getInputTemplate(id, 'credit') }
            </td>
            <td class="py-4 pl-3 text-sm text-center font-medium whitespace-nowrap">
                <button data-modal="delete" type="button" class="bg-red-600 flex justify-center w-6 h-6 items-center transition-colors duration-150 rounded-full shadow-lg hover:bg-red-500">
                    <i class="fas fa-times  text-white"></i>                        
                </button>
            </td>
        </tr>
    `;

    this.isContainerDefined = () => this.container !== null
    

    const getBankSelectSelectors = (rowsIDS) => {

        if (rowsIDS && rowsIDS.length > 0){
            return rowsIDS.map((el) => `tr[data-id="${el}"] select`).join(',');
        }

        return '';
    };

    const updateBankSelects = (container = null, availableBanks = [], selectors = '') => {
        if (selectors === ''){ return false };

        const selectSelectorsElems = container.querySelectorAll(selectors);

        selectSelectorsElems.forEach(function(el) {
            let options = [el.value, ...availableBanks];
            const html = options.map(el => `<option value="${el}">${el}</option>`).join('');
            el.innerHTML = html;
        });

        return true;
    }

    const updateTableIDColumn = (container) => {
        const colsID = container.querySelectorAll(`td[data-table="num-col"]`);

        for (let i = 0; i < colsID.length; i++){
            colsID[i].innerHTML = i + 1;
        }
    }
}

export default SalePointTable;