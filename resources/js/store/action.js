import { store } from './index'

export const STORE_CURRENT_DOLLAR_EXCHANGE_VALUE = 'STORE_CURRENT_DOLLAR_EXCHANGE_VALUE'

export const STORE_DOLLAR_EXCHANGE_VALUE = 'STORE_DOLLAR_EXCHANGE_VALUE'

function storeCurrentDollarExchange(dollarExchange = {}) {
  return { type: STORE_CURRENT_DOLLAR_EXCHANGE_VALUE, dollarExchange }
}

function storeDollarExchange(dollarExchange = {}) {
  return { type: STORE_DOLLAR_EXCHANGE_VALUE, dollarExchange }
}
export const boundStoreCurrentDollarExchange = (obj) => store.dispatch(storeCurrentDollarExchange(obj))

export const boundStoreDollarExchange = (obj) => store.dispatch(storeDollarExchange(obj))