import axiosClient from '../../utilities/axiosClient';

const postDollarExchange = function(obj){
    return asyncFunction(axiosClient.post('/dollar_exchange', obj))
};

const getDollarExchange = function(){
    return asyncFunction(axiosClient.get('/dollar_exchange'))
};

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)

export { postDollarExchange, getDollarExchange };