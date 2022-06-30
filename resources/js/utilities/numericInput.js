import Inputmask from "inputmask";

const numericInput = Inputmask("(999){+|1}", {
    numericInput: true,
    placeholder: "0",
    definitions: {
        "0": {
            validator: "[0-9\uFF11-\uFF19]"
        }
    }
})

export default numericInput;