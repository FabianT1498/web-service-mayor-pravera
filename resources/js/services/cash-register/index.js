import axiosClient from '../../utilities/axiosClient';

const getCashRegisterUsersWithoutRecords = function(date){
    return asyncFunction(axiosClient.get(`/cash_register/users_without_record/${date}`))
};

const getTotalsToCashRegisterUserSaint = function(obj){
  return asyncFunction(axiosClient.get(`/cash_register/saint/totals/${obj.cashRegisterUser}/${obj.date}/${obj.date}`))
};

const getTotalsToCashRegisterUser = function(id){
  return asyncFunction(axiosClient.get(`/cash_register/totals/${id}`))
};

const getMoneyBackToCashRegisterUserSaint = function(obj){
  return asyncFunction(axiosClient.get(`/vales_vueltos_facturas/saint/totals/${obj.user}/${obj.date}/${obj.date}`))
};

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)


export { getCashRegisterUsersWithoutRecords, getTotalsToCashRegisterUserSaint, getTotalsToCashRegisterUser, getMoneyBackToCashRegisterUserSaint };
