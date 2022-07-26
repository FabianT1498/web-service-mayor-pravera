import SuggestionModalView from '_views/SuggestionModalView'
import SuggestionModalPresenter from '_presenters/SuggestionModalPresenter'

export default {
    DOMElements: {
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        intervalLink: document.querySelector('a[data-generate-pdf=interval-report]'),
        productsTBody: document.querySelector('#products-tbody'),
        databaseNameSelect: document.querySelector('#databaseName'),
    },
    handleClickProductSuggestion: function() {
        let self = this;
        return async (event) => {
            let button = event.target.closest('button');
            if (button && button.getAttribute('data_product_id')){
                let productID = button.getAttribute('data_product_id');
                this.productSuggestionsPresenter.setCodProd(productID);
            }
        }
    },
    handleClickPaginator: function(pageInput, form){
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
    },
    handleDatabaseChange: function() {
        let self = this;
        return (e) => {
            this.DOMElements.formFilter.submit();
        }
    },
    handleCheck: function(event){
        event.target.value = event.target.value === '1' ? '0' : '1'; 
    },
    initEventListeners() {
        this.DOMElements.productsTBody.addEventListener('click', this.handleClickProductSuggestion());

        this.DOMElements.formFilter.querySelector('#thereExistance').addEventListener('click', this.handleCheck);

        this.DOMElements.databaseNameSelect.addEventListener('change', this.handleDatabaseChange())
    
        if (this.DOMElements.pagesLinksContainer){
            this.DOMElements.pagesLinksContainer.addEventListener('click', this.handleClickPaginator(this.DOMElements.pageInput, this.DOMElements.formFilter));
        }
    },
    init(){
        this.initEventListeners();

        let suggestionContainer = document.querySelector('#suggestion-modal')
        this.productSuggestionsPresenter = new SuggestionModalPresenter()
        this.productSuggestionsView = new SuggestionModalView(this.productSuggestionsPresenter)
        this.productSuggestionsView.init(suggestionContainer)
    }
}