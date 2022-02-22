import { STORE_DOLLAR_EXCHANGE_VALUE } from './action'

let initialState = {
    dollarExchangeValue: 0,
}

export const reducer = (state = initialState, action) => {
    switch (action.type) {
      case STORE_DOLLAR_EXCHANGE_VALUE:
        return {...state, dollarExchange: action.dollarExchange}
      default:
        return state
    }
}