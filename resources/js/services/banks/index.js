import axiosClient from '../../utilities/axiosClient';

const getAllBanks = async function(obj){
    try {
        const result = await axiosClient.get('/banks');
        return result.data.data;

    } catch(e) {
        console.log(e);
    }
};

export { getAllBanks };