import PubSub from "pubsub-js";

import { formatAmount } from '_utilities/mathUtilities'
import LiquidMoneyModal from "./LiquidMoneyModal";

const LiquidMoneyModalDollars = function({currency}) {
    this.currency = currency || '$';

    LiquidMoneyModal.call(this);

    this.init = function(container){
        LiquidMoneyModal.prototype.init.call(this, container, "liquid_money_dollars");
        container.addEventListener("keydown", this.keyDownEventHandler);
    }

    const handleUpdateConvertionColEvent = (event) => {
        const row = event.target.closest('tr');
        const lastDollarExchangeValEl = document.querySelector(`#last-dollar-exchange-bs-val`);
        const lastDollarExchangeVal = lastDollarExchangeValEl ? parseFloat(lastDollarExchangeValEl.value) : 0
        
        PubSub.publish('updateConvertionCol', {
            row,
            lastDollarExchangeVal,
            amount: formatAmount(event.target.value)
        });
    }

    this.keypressEventHandler = function(event){
        
        event.preventDefault();
    
        let key = event.key || event.keyCode;

        if (isFinite(key)){ // Handle case to convert dollar to Bs.S
            handleUpdateConvertionColEvent(event)
        }
        else if (key === 13 || key === 'Enter'){ // Handle new table's row creation
            PubSub.publish(`addRow.${currency}`);
        }
    };

    this.keyDownEventHandler = function(event){
        let key = event.key || event.keyCode;

        if (key === 8 || key === 'Backspace'){ // Handle case to convert dollar to Bs.S
            handleUpdateConvertionColEvent(event)
        }
    };

}

LiquidMoneyModalDollars.prototype = Object.create(LiquidMoneyModal.prototype);

export default LiquidMoneyModalDollars;