import axiosClient from '../../utilities/axiosClient';

const getBillPayable = function({numeroD, codProv, billType}){
    return asyncFunction(axiosClient.get(`/bill_payable/${codProv}/${numeroD}/${billType}`))
};

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)


export { getBillPayable };
