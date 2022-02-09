import { storeDollarExchange } from './../../services/dollar-exchange';
import Inputmask from "inputmask";

const dollarExchangeInput = document.getElementById('dollar_exchange_bs');
    
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
        const result = await storeDollarExchange({
            'bs_exchange': dollarExchangeInput.value
        });
    
        console.log(result)
    }
    catch(e){
        console.log(e);
    }
}

document.getElementById('update-dolar-exchange-rate').addEventListener('click', updateDollarExchangeBsHandler);