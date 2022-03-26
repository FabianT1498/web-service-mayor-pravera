import { STORE_DOLLAR_EXCHANGE_VALUE } from './action'

let initialState = {
    dollarExchange: {
      value: 0,
      createdAt: ''
    },
    lastAction: ''
}

export const reducer = (state = initialState, action) => {
    switch (action.type) {
      case STORE_DOLLAR_EXCHANGE_VALUE:
        return {...state, dollarExchange: { ...state.dollarExchange, ...action.dollarExchange}, lastAction: action.type}
      default:
        return state
    }
}