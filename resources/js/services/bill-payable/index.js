import axiosClient from '../../utilities/axiosClient';

const getBillPayable = function({numeroD, codProv}){
    return asyncFunction(axiosClient.get(`/bill_payable/${codProv}/${numeroD}`))
};

const storeBillPayable = function(data){
    return asyncFunction(axiosClient.post('/bill_payable/', data))
};

const getBillPayableSchedule = function(id){
    return asyncFunction(axiosClient.get(`/schedule/${id}`))
};

const getProviders = function(q){
    return asyncFunction(axiosClient.get(`/provider?descrip=${q}`))
};

const linkBillPayableToSchedule = function(data){
    return asyncFunction(axiosClient.post(`/bill_payable/${data.scheduleID}`, data))
}

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)


export { getBillPayable, storeBillPayable, getBillPayableSchedule, linkBillPayableToSchedule, getProviders};
