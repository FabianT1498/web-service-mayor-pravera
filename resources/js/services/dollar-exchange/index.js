import axiosClient from '../../utilities/axiosClient';

const postDollarExchange = function(obj){
    return asyncFunction(axiosClient.post('/dolar_exchange', obj))
};

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)

export { postDollarExchange };