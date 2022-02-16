import PubSub from "pubsub-js";
import Inputmask from "inputmask";

const DecimalInput = function(){
   
    let decimalMaskOptions = {
        alias:'decimal',
        suffix: '$',
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
        PubSub.subscribe('attachMask', attachMask)
    };

    const attachMask = (msg, data) => {
        let input = data.element;
        let currency = data.currency; 
        decimalMaskOptions.suffix = ` ${currency}`;
        new Inputmask(decimalMaskOptions).mask(input)
    }
}

export default DecimalInput;