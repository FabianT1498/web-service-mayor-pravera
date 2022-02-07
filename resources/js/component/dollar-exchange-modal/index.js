import { storeDollarExchange } from './../../services/dollar-exchange';

document.getElementById('update-dolar-exchange-rate').addEventListener('click', function(event){
    const dollarExchangeInput = document.getElementById('dollar_exchange_bs');
    const result = storeDollarExchange({
        'bs_exchange': dollarExchangeInput.value
    });
    console.log(result)
})