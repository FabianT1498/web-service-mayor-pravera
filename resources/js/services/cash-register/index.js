import axiosClient from '../../utilities/axiosClient';

const getCashRegisterUsersWithoutRecords = function(date){
    return asyncFunction(axiosClient.get(`/cash_register/users_without_record/${date}`))
};

const getTotalsToCashRegisterUser = function(obj){
    return asyncFunction(axiosClient.get(`/cash_register/totals/${obj.cashRegisterUser}/${obj.date}`))
};

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)


export { getCashRegisterUsersWithoutRecords, getTotalsToCashRegisterUser };