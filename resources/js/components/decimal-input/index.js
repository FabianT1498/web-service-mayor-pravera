import PubSub from "pubsub-js";
import Inputmask from "inputmask";

import CURRENCY_SYMBOLS_MAP from '_assets/currencies';

const DecimalInput = function(currency){
    this.currency = currency ? currency : 'dollar';
    this.suffix = CURRENCY_SYMBOLS_MAP[currency] || '$'

    let decimalMaskOptions = {
        alias:'decimal',
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
    }
    
    this.init = () =>{
        PubSub.subscribe(`attachMask.${this.currency}`, attachMask)
    };

    const attachMask = (msg, data) => {
        let input = data.input;
        decimalMaskOptions['suffix'] = ` ${this.suffix}`;
        new Inputmask(decimalMaskOptions).mask(input)
    }
}

export default DecimalInput;