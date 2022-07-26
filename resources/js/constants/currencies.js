const CURRENCIES = Object.freeze(
    {
        BOLIVAR: 'bs',
        DOLLAR: 'dollar'
    }
)

const SIGN  = Object.freeze({
    [CURRENCIES.DOLLAR]: '$',
    [CURRENCIES.BOLIVAR]: 'Bs',
})

export { CURRENCIES, SIGN };