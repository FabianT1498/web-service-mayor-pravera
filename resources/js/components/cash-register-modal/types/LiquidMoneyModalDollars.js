import PubSub from "pubsub-js";

import LiquidMoneyModal from "./LiquidMoneyModal";

const LiquidMoneyModalDollars = function({currency}) {
    this.currency = currency || '$';

    LiquidMoneyModal.call(this);

    this.init = function(container){
        LiquidMoneyModal.prototype.init.call(this, container);
        container.addEventListener("keydown", keyDownEventHandler);
    }

    this.keyDownEventHandler = function(event){
        let key = event.key || event.keyCode;

        if (key === 8 || key === 'Backspace'){ // Handle case to convert dollar to Bs.S
            updateConvertionCol(event)
        }
    };

    this.keypressEventHandler = function(event){
        
        event.preventDefault();

        let key = event.key || event.keyCode;

        if (isFinite(key)){ // Handle case to convert dollar to Bs.S
            let row = event.target.closest('tr');
            let dollarExchangeBs = parseFloat(document.querySelector(`#last-dollar-exchange-bs-val`).value);
            PubSub.publish('updateConvertionCol', {
                row,
                dollarExchangeBs,
                amount: event.target.value
            });
        }
        else if (key === 13 || key === 'Enter'){ // Handle new table's row creation
            PubSub.publish('addRow', {currency: this.currency});
        }
    };
}

LiquidMoneyModalDollars.prototype = Object.create(LiquidMoneyModal.prototype);

export default LiquidMoneyModalDollars;