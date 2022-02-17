import PubSub from "pubsub-js";

import { formatAmount } from '_utilities/mathUtilities'

const DenominationsTable = function(name, currency){

    this.name = name || "";
    this.currency = currency || "dollar";

    this.init = (container) => {
        this.container = container;
     
        PubSub.subscribe(`getTotal.denominations.${this.currency}`, getTotal); 
    }

    const getTotal = (msg, data) => {
        if (!this.container){
            return;
        }
        
        const tBody = this.container.querySelector('tBody')
        let inputs = tBody.querySelectorAll('input')
        const total = Array.from(inputs).reduce((acc, el) => {
            let denomination = parseFloat(el.getAttribute('data-denomination'));
            let num = formatAmount(el.value)
            return acc + (num * denomination);
        }, 0);

        document.getElementById(`total_${this.name}`).value = total > 0 ? total : 0;
    }
}

export default DenominationsTable;