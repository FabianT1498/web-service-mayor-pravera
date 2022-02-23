import { store } from './index'

export const STORE_DOLLAR_EXCHANGE_VALUE = 'STORE_DOLLAR_EXCHANGE_VALUE'

function storeDollarExchange(dollarExchange = {}) {
  return { type: STORE_DOLLAR_EXCHANGE_VALUE, dollarExchange }
}

const boundStoreDollarExchange = (obj) => store.dispatch(storeDollarExchange(obj))

export { boundStoreDollarExchange }