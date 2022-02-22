import { store } from './index'


export const STORE_DOLLAR_EXCHANGE_VALUE = 'STORE_DOLLAR_EXCHANGE_VALUE'

function storeDollarExchange(value) {
  return { type: STORE_DOLLAR_EXCHANGE_VALUE, value }
}

const boundStoreDollarExchange = (value) => store.dispatch(storeDollarExchange(value))

export { boundStoreDollarExchange }