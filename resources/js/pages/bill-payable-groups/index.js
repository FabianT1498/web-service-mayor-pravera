import AutocompletePresenter from '_components/autocomplete/presenter'
import AutocompleteView from '_components/autocomplete/view'

import BillPayableGroupScheduleView from '_views/BillPayableGroupScheduleView'
import BillPayableGroupSchedulePresenter from '_presenters/BillPayableGroupSchedulePresenter'

import { getProviders } from '_services/bill-payable';

export default {
    DOMElements: {
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        billsPayableTBody: document.querySelector('#billsPayableTBody'),
        cleanFormBtn: document.querySelector('#cleanFormBtn'),
    },
    handleClickWrapperCleanFormBtn: function(){
        return (event) => {
            this.DOMElements.formFilter.querySelector('#provider_search_hidden').value = ''
            this.DOMElements.formFilter.querySelector('#provider_search_input').value = ''
            this.DOMElements.formFilter.querySelector('#status').value = ''
            this.DOMElements.formFilter.submit()
        }
    },
    handleClick: function(){
        return (event) => {

            const modalBtn = event.target.closest('button');

            let groupID = modalBtn && modalBtn.getAttribute('data-groupID')
                    ? modalBtn.getAttribute('data-groupID') 
                    : null

            if (groupID){
                this.billPayableGroupSchedulePresenter.setBillPayableGroup(groupID)
            }
        }
    },
    initEventListener(){

        this.DOMElements.billsPayableTBody.addEventListener('click', this.handleClick())

        this.DOMElements.cleanFormBtn.addEventListener('click', this.handleClickWrapperCleanFormBtn())

        if (this.DOMElements.pagesLinksContainer){
            this.DOMElements.pagesLinksContainer.addEventListener('click', function(pageInput, form){
                return (e) => {
                    e.preventDefault();
                    let anchor = e.target.closest('a');
                    if (anchor){
                        let url = new URL(anchor.href);
                        let params = new URLSearchParams(url.search);
                        let page = params.get("page");
                        pageInput.value = page;
                        form.submit();
                    }
                }
            }(this.DOMElements.pageInput, this.DOMElements.formFilter));
        }
    },
    init(){
        this.initEventListener()
        
        let providerSearchBoxElement =  document.querySelector('#provider_search');
        let providerSearchBoxPresenter = new AutocompletePresenter(getProviders);
        let providerSearchBoxView = new AutocompleteView(providerSearchBoxPresenter);
        providerSearchBoxView.init(providerSearchBoxElement)

        let billPayableScheduleContainer = document.querySelector('#bill_payable_schedules')
        this.billPayableGroupSchedulePresenter = new BillPayableGroupSchedulePresenter();

        this.billPayableGroupScheduleView = new BillPayableGroupScheduleView(this.billPayableGroupSchedulePresenter);
        this.billPayableGroupScheduleView.init(billPayableScheduleContainer)
    }
}
