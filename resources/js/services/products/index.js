import axiosClient from '../../utilities/axiosClient';

const getProductSuggestions = function(codProduct){
    return asyncFunction(axiosClient.get(`/products/suggestions/${codProduct}`))
};


const storeProductSuggestions = function(data){
    return asyncFunction(axiosClient.post(`/products/suggestions`, data))
}

const asyncFunction = (promise) => promise.then(res => res).catch(err => err)


export { getProductSuggestions, storeProductSuggestions };
