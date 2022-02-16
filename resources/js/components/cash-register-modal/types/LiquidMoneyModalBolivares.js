import PubSub from "pubsub-js";

import LiquidMoneyModal from "./LiquidMoneyModal";

const LiquidMoneyModalBolivares = function({currency}) {
    LiquidMoneyModal.call(this);

    this.currency = currency || 'Bs.S';
    
    this.init = function(container){
        LiquidMoneyModal.prototype.init.call(this, container, "liquid_money_bolivares");
    }
    
    this.keypressEventHandler = function(event){
        event.preventDefault();

        let key = event.key || event.keyCode;
        
        if (key === 13 || key === 'Enter'){ // Handle new table's row creation
            PubSub.publish(`addRow.${currency}`);
        }
    };
    
    
}

LiquidMoneyModalBolivares.prototype = Object.create(LiquidMoneyModal.prototype);
LiquidMoneyModalBolivares.prototype.constructor = LiquidMoneyModalBolivares;

export default LiquidMoneyModalBolivares;