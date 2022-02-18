import PubSub from "pubsub-js";

import RecordMoneyModal from "./RecordMoneyModal";

import { CURRENCIES} from '_assets/currencies';

const RecordMoneyModalBolivares = function({currency, method}) {
    RecordMoneyModal.call(this);

    this.currency = currency || CURRENCIES.BOLIVAR;
    this.method = method || 'money'
    
    this.init = function(container, containerID){
        RecordMoneyModal.prototype.init.call(this, container, containerID);
    }
    
    this.keypressEventHandlerWrapper = function(currency, method){
        return (event) => {
            event.preventDefault();
    
            let key = event.key || event.keyCode;
            
            if (key === 13 || key === 'Enter'){ // Handle new table's row creation
                PubSub.publish(`addRow.${method}.${currency}`);
            }
        }
    };
    
    
}

RecordMoneyModalBolivares.prototype = Object.create(RecordMoneyModal.prototype);
RecordMoneyModalBolivares.prototype.constructor = RecordMoneyModalBolivares;

export default RecordMoneyModalBolivares;