import { storeDollarExchange } from '../../services/dollar-exchange';
import Inputmask from "inputmask";

const dollarExchangeInput = document.getElementById('dollar-exchange-bs-input');
    
(function(input){

    let decimalMaskOptions = {
        alias:'decimal',
        suffix:' Bs.S',
        positionCaretOnClick: "radixFocus",
        digits: 2,
        radixPoint: ",",
        _radixDance: true,
        numericInput: true,
        placeholder: "0",
        definitions: {
            "0": {
                validator: "[0-9\uFF11-\uFF19]"
            },
        },
    };

    (new Inputmask(decimalMaskOptions)).mask(dollarExchangeInput);

})();

const updateDollarExchangeBsHandler = async function(event){
    

    try {
        const response = await storeDollarExchange({
            'bs_exchange': dollarExchangeInput.value
        });

        console.log(response.data.data)
        
        if ([201, 200].includes(response.status)){
            // Show modal to succesfully value store
            toggleModal('dollar-exchange-modal', false);
            toastr.success('La tasa del dolar ha sido cambiada con exito');

            let lastBsValueInput = document.querySelector('input#last-dollar-exchange-bs-val');
    
            // Check if use is in cash register page
            if (lastBsValueInput){
                lastBsValueInput.value = response.data.data['bs_exchange'];
                document.querySelector('#last-dollar-exchange-bs-date').innerHTML = response.data.data['created_at']
                document.querySelector('#last-dollar-exchange-bs-label').innerHTML = `${response.data.data['bs_exchange']} Bs.s`
            }
        }
    }
    catch(e){
        console.log(e);
        toastr.error('No se pudo guardar el valor de la tasa del dolar');
    }
}

document.getElementById('update-dollar-exchange-btn').addEventListener('click', updateDollarExchangeBsHandler);