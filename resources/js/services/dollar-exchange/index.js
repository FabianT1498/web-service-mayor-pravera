import axiosClient from '../../utilities/axiosClient';

const storeDollarExchange = function(obj){
    try {
        const result = axiosClient.post('/api/dolar_exchange', obj);
    } catch(e) {
        console.log(e);
    }
};

export { storeDollarExchange };