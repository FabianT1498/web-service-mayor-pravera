import axiosClient from '../../utilities/axiosClient';

const storeDollarExchange = async function(obj){
    try {
        const result = await axiosClient.post('/dolar_exchange', obj);
        return result;
        
        // const response = await fetch(`http://127.0.0.1:8001/dolar_exchange`, {
        //     method: 'POST',
        //     headers: {
        //         'X-Requested-With': 'XMLHttpRequest',
        //         'Accept': 'Application/json',
        //         'Content-type': 'Application/json',
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     },
        //     body: JSON.stringify(obj)
        // });

        // return response.json();

    } catch(e) {
        console.log(e);
    }
};

export { storeDollarExchange };