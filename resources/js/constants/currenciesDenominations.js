import { CURRENCIES } from "./currencies";

const CURRENCIES_DENOMINATIONS  = Object.freeze({
    [CURRENCIES.DOLLAR]: [0.50, 1, 2, 5, 10, 20,50, 100],
    [CURRENCIES.BOLIVAR]: [0.50, 1, 2, 5, 10, 20, 50,100, 200, 500],
})

export default CURRENCIES_DENOMINATIONS;