import { storeDollarExchange } from './../../services/dollar-exchange';
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
        
        console.log(response.data);
     
    }
    catch(e){
        console.log(e);
    }
}

document.getElementById('update-dollar-exchange-btn').addEventListener('click', updateDollarExchangeBsHandler);