import Inputmask from "inputmask";

export default function(){
    let inputs = document.querySelectorAll('[data-currency^="amount"]');

    let moneyFormat = new Inputmask("(.999){+|1},00", {
        positionCaretOnClick: "radixFocus",
        radixPoint: ",",
        _radixDance: true,
        numericInput: true,
        placeholder: "0",
        definitions: {
            "0": {
                validator: "[0-9\uFF11-\uFF19]"
            }
        },
   })
    
    inputs.forEach(e => moneyFormat.mask(e))
    
}