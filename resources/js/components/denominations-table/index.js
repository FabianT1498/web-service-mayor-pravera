import { numericInput } from '_utilities/numericInput';

import { CURRENCIES} from '_constants/currencies';

const DenominationsTable = function(){

    this.init = (container, name, currency) => {
        this.container = container;
        this.name = name;
        this.currency = currency || CURRENCIES.DOLLAR;
        setInitialMask();
    }

    this.isContainerDefined = function(){
        return this.container !== null
    }

    const setInitialMask = () => {
        if (!this.isContainerDefined()){
            return;
        }
        
        const tBody = this.container.querySelector('tbody');
        let inputs = tBody.querySelectorAll('input');
        inputs.forEach(el => {
            numericInput.mask(el);
        })
    }
}

export default DenominationsTable;