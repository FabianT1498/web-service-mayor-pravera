import { CURRENCIES } from "./currencies";

const PAYMENT_CODES  = Object.freeze({
    '01': 'pointSaleBs',
    '02': 'pointSaleBs',
    '03': 'todoTicket',
    '05': 'pagoMovilBs',
    '07': 'zelleDollar',
    '08': 'pointSaleDollar'
})

const TYPE_BILLS  = Object.freeze({
    'A': 'liquidMoneyBs',
    'B' : 'liquidMoneyDollar'
})



export {PAYMENT_CODES, TYPE_BILLS};