import PubSub from "pubsub-js";

import { formatAmount } from '_utilities/mathUtilities'
import RecordMoneyModal from "./RecordMoneyModal";
import { CURRENCIES} from '_constants/currencies';

const RecordMoneyModalDollars = function({currency, method}) {
    this.currency = currency || CURRENCIES.DOLLAR;
    this.method = method || 'money'

    RecordMoneyModal.call(this);

    this.init = function(container, containerID){
        RecordMoneyModal.prototype.init.call(this, container, containerID);
        container.addEventListener("keydown", this.keyDownEventHandler);
    }

    const handleUpdateConvertionColEvent = (event, currency, method) => {
        const row = event.target.closest('tr');
        // const lastDollarExchangeValEl = document.querySelector(`#last-dollar-exchange-bs-val`);
        // const lastDollarExchangeVal = lastDollarExchangeValEl ? parseFloat(lastDollarExchangeValEl.value) : 0

        const lastDollarExchangeVal = 5.12;
        
        PubSub.publish(`updateConvertionCol.${method}.${currency}`, {
            row,
            lastDollarExchangeVal,
            amount: formatAmount(event.target.value)
        });
    }

    this.keypressEventHandlerWrapper = function(currency, method){
        return (event) => {
            event.preventDefault();
        
            let key = event.key || event.keyCode;
    
            if (isFinite(key)){ // Handle case to convert dollar to Bs.S
                handleUpdateConvertionColEvent(event, currency, method)
            }
            else if (key === 13 || key === 'Enter'){ // Handle new table's row creation
                PubSub.publish(`addRow.${method}.${currency}`);
            }
        }
    };

    this.keyDownEventHandler = function(event){
        let key = event.key || event.keyCode;

        if (key === 8 || key === 'Backspace'){ // Handle case to convert dollar to Bs.S
            handleUpdateConvertionColEvent(event)
        }
    };

}

RecordMoneyModalDollars.prototype = Object.create(RecordMoneyModal.prototype);

export default RecordMoneyModalDollars;