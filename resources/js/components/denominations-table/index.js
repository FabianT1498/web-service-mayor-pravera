import { formatAmount } from '_utilities/mathUtilities'

import { CURRENCIES} from '_constants/currencies';

const DenominationsTable = function(){

    this.init = (container, name, currency) => {
        this.container = container;
        this.name = name;
        this.currency = currency || CURRENCIES.DOLLAR;
    }

    this.isContainerDefined = function(){
        return this.container !== null
    }

    this.getTotal = function(){
        if (!this.isContainerDefined){
            return 0;
        }
        
        const tBody = this.container.querySelector('tBody')
        let inputs = tBody.querySelectorAll('input')
        let total = Array.from(inputs).reduce((acc, el) => {
            let denomination = parseFloat(el.getAttribute('data-denomination'));
            let num = formatAmount(el.value)
            return acc + (num * denomination);
        }, 0);
        
        return total;
    }
}

export default DenominationsTable;