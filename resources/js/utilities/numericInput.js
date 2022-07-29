import Inputmask from "inputmask";

const numericInput = Inputmask("(999){+|1}", {
    alias: 'numeric',
    numericInput: true,
    placeholder: "0",
    definitions: {
        "0": {
            validator: "[0-9\uFF11-\uFF19]"
        }
    }
})

const percentageInput = new Inputmask({
    alias:'percentage',
    positionCaretOnClick: "radixFocus",
    digits: 2,
    radixPoint: ".",
    _radixDance: true,
    numericInput: false,
    placeholder: "0",
    definitions: {
        "0": {
            validator: "[0-9\uFF11-\uFF19]"
        },
    },
    suffix: ' %'
})

export { numericInput, percentageInput };