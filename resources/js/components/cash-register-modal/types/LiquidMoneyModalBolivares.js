import PubSub from "pubsub-js";

import LiquidMoneyModal from "./LiquidMoneyModal";

const LiquidMoneyModalBolivares = function({currency}) {
    this.currency = currency || 'Bs.S';
    LiquidMoneyModal.call(this);

    this.keypressEventHandler = function(event){
        event.preventDefault();

        let key = event.key || event.keyCode;

        if (key === 13 || key === 'Enter'){ // Handle new table's row creation
            PubSub.publish('addRow', {currency: this.currency});
        }
    };
}

LiquidMoneyModalBolivares.prototype = Object.create(LiquidMoneyModal.prototype);

export default LiquidMoneyModalBolivares;