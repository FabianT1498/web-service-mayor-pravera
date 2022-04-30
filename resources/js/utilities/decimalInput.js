import Inputmask from "inputmask";
import { SIGN as CURRENCY_SYMBOLS_MAP, CURRENCIES} from '_constants/currencies';

const decimalMaskOptions = {
    alias:'currency',
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
}

const decimalInputs =  {
    [CURRENCIES.BOLIVAR]: new Inputmask({...decimalMaskOptions, suffix: ' '.concat(CURRENCY_SYMBOLS_MAP[CURRENCIES.BOLIVAR])}),
    [CURRENCIES.DOLLAR]: new Inputmask({...decimalMaskOptions, suffix: ' '. concat(CURRENCY_SYMBOLS_MAP[CURRENCIES.DOLLAR])}),
}

export { decimalInputs };