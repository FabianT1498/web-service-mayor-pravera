import { CURRENCIES } from "./currencies";

const PAYMENT_CODES  = Object.freeze({
    '01': 'pointSaleBs',
    '02': 'pointSaleBs',
    '03': 'todoticketBs',
    '04': 'amexBs',
    '05': 'pagoMovilBs',
    '07': 'zelleDollar',
    '08': 'pointSaleDollar'
})

const PAYMENT_CURRENCIES =  Object.freeze({
    '01': 'bs',
    '02': 'bs',
    '03': 'bs',
    '04': 'bs',
    '05': 'bs',
    '07': 'dollar',
    '08': 'dollar'
})

export {PAYMENT_CODES,  PAYMENT_CURRENCIES};