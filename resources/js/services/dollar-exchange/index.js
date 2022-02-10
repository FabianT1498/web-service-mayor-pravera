import axiosClient from '../../utilities/axiosClient';

const storeDollarExchange = async function(obj){
    try {
        const result = await axiosClient.post('/dolar_exchange', obj);
        return result;

    } catch(e) {
        console.log(e);
    }
};

export { storeDollarExchange };