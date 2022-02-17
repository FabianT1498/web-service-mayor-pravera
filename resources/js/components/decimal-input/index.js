import PubSub from "pubsub-js";
import Inputmask from "inputmask";

import CURRENCY_SYMBOLS_MAP from '_assets/currencies';

const DecimalInput = function(){
    
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
        PubSub.subscribe(`attachMask`, attachMask)
    };

    const attachMask = (msg, data) => {
        let currency = data && data?.currency ? data.currency : 'dollar';
        let suffix = CURRENCY_SYMBOLS_MAP[currency] || '$'
        let input = data.input;
        decimalMaskOptions['suffix'] = ` ${suffix}`;
        new Inputmask(decimalMaskOptions).mask(input)
    }
}

export default DecimalInput;