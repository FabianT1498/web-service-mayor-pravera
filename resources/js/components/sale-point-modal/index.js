import PubSub from "pubsub-js";

import SalePointTable from "_components/sale-point-table";

const SalePointModalPrototype = {
    init(container){
        
        const tableContainer = container.querySelector('table')
        const table = new SalePointTable(this.name, this.currency);
        table.init(tableContainer);
    
        container.addEventListener('click', this.handleClickEvent);
        container.addEventListener('change', this.handleOnChangeEvent);
    },
    handleClickEvent(event){
        const closest = event.target.closest('button');

        if(closest && closest.tagName === 'BUTTON'){
            
            const action = closest.getAttribute('data-modal')

            if (!action){
                return;
            }

            if (action === 'add'){
                
                PubSub.publish('addRow.salePoint');
                
            } else if (action === 'remove'){
                // Logic is here
            }
        }
    },
    handleOnChangeEvent(event){
    
        let row = event.target.closest('tr');

        if (row && row.getAttribute('data-id')){

            let rowID = row.getAttribute('data-id');
            
            // Get the selected index
            let index = event.target.selectedIndex;
            
            // Get the new value
            let newSelectValue = event.target.options[index].value;

            PubSub.publish('changeSelect.salePoint', {rowID, newSelectValue});
        }
    }
}

const SalePointModal = function (name, currency){
    this.name = name;
    this.currency = currency
}

SalePointModal.prototype = SalePointModalPrototype;
SalePointModal.prototype.constructor = SalePointModal;

export default SalePointModal;