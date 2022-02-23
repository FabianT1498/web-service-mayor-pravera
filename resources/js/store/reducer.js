import { STORE_DOLLAR_EXCHANGE_VALUE } from './action'

let initialState = {
    dollarExchange: {
      value: 0,
      createdAt: ''
    }
}

export const reducer = (state = initialState, action) => {
    switch (action.type) {
      case STORE_DOLLAR_EXCHANGE_VALUE:
        return {...state, dollarExchange: { ...state.dollarExchange, ...action.dollarExchange}}
      default:
        return state
    }
}