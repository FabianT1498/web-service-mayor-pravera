export default {
    DOMElements: {
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        cashRegisterTBody: document.querySelector('#cash-register-tbody'),
        loadingModalEl: document.querySelector('#modal-loading'),
        intervalLink: document.querySelector('a[data-generate-pdf=interval-report]'),
    },
    changeWrapper: (form) => {
        return (event) => {
            form.submit();
        }
    },
    handleClickCancelLoading: function() {
        let self = this;
        return (event) => {
            event.preventDefault()
            let modal = new Modal(self.DOMElements.loadingModalEl, {placement: 'center-bottom'});
            modal.hide();
            return false;
        }
    },
    handleClickAcceptCashRegisterBtn: function() {
        let self = this;
        return (event) => {
            let form = document.querySelector('#close-cash-register-form-' + self.lastClickedCloseBtnID);
            form.submit();
        }
    },
    init(){
        this.DOMElements.formFilter.addEventListener('change', this.changeWrapper(this.DOMElements.formFilter));
    
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
    }
}