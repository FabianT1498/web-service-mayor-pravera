import DollarExchangeModalPresenter from "_presenters/DollarExchangeModalPresenter";
import DollarExchangeModalView from "_views/DollarExchangeView";

export default (function(){
    let dollarExchangeModalPresenter = new DollarExchangeModalPresenter();
    let dollarExchangeModalView = new DollarExchangeModalView(dollarExchangeModalPresenter)
    dollarExchangeModalView.init();
})()