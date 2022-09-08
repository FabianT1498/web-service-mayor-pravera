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

const getBillPayableGroup = function({codProv}){
    return asyncFunction(axiosClient.get(`/bill_payable/group/${codProv}`))
}

const storeBillPayableGroup = function(data){
    return asyncFunction(axiosClient.post('/bill_payable/group', data))
};

const updateBillPayableGroup = function(id, data){
    return asyncFunction(axiosClient.put(`/bill_payable/group/${id}`, data))
};

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)


export { getBillPayableGroup, getBillPayable, storeBillPayable, getBillPayableSchedule, linkBillPayableToSchedule, getProviders, storeBillPayableGroup, updateBillPayableGroup };
