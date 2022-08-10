import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';
import DateRangePicker from '@themesberg/tailwind-datepicker/DateRangePicker';
import es from '@themesberg/tailwind-datepicker/locales/es';

export default {
    DOMElements: {
        dateRangePicker: document.querySelector('#date_range_picker'),
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        acceptCashRegisterCloseBtn: document.querySelector('#accept-cash-register-close'),
        cashRegisterTBody: document.querySelector('#cash-register-tbody'),
        loadingModalEl: document.querySelector('#modal-loading'),
        intervalLink: document.querySelector('a[data-generate-pdf=interval-report]'),
        cashRegisterAlert: document.querySelector('#cash-register-alert'),
    },
    lastClickedCloseBtnID: -1,
    changeDateEventHandlerWrapper: (form) => {
        return (event) => {
            form.submit();
        }
    },
    changeWrapper: (form) => {
        return (event) => {
            form.submit();
        }
    },
    handleClickCashRegister: function() {
        let self = this;
        return (event) => {
            let button = event.target.closest('button');
            let link = event.target.closest('a');

            if (button && button.getAttribute('data_cash_register_id')){
                self.lastClickedCloseBtnID = button.getAttribute('data_cash_register_id');
            } else if(link && link.getAttribute('data-generate-pdf')){
                
                let dateRangePicker = new DateRangePicker(this.DOMElements.dateRangePicker, {
                    format: 'dd-mm-yyyy',
                    language: 'es'
                });

                let splittedStartDate = dateRangePicker.inputs[0].value.split('-')
                let splittedEndDate = dateRangePicker.inputs[1].value.split('-')

                let startDate = new Date(splittedStartDate[1] + '-' + splittedStartDate[0] + '-' + splittedStartDate[2])
                let endDate = new Date(splittedEndDate[1] + '-' + splittedEndDate[0] + '-' + splittedEndDate[2])
        
                let timeDiff = endDate.getTime() - startDate.getTime();  
  
                //calculate days difference by dividing total milliseconds in a day  
                let dayDiff = timeDiff / (1000 * 60 * 60 * 24);
                
                if (dayDiff + 1 > 10){
                    event.preventDefault();
                    this.DOMElements.cashRegisterAlert.classList.remove('hidden', 'opacity-0')
                   
                } else {
                    let modal = new Modal(self.DOMElements.loadingModalEl, {placement: 'center-bottom'});
                    modal.show();
                }
                        
            }
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
        Object.assign(Datepicker.locales, es);

        let dateRangePicker = new DateRangePicker(this.DOMElements.dateRangePicker, {
            format: 'dd-mm-yyyy',
            language: 'es',
        });

        /** Filtrar registros  */
        dateRangePicker.datepickers[0].element.addEventListener('hide', this.changeDateEventHandlerWrapper(this.DOMElements.formFilter));
        dateRangePicker.datepickers[1].element.addEventListener('hide', this.changeDateEventHandlerWrapper(this.DOMElements.formFilter));
        
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

        this.DOMElements.cashRegisterTBody.addEventListener('click', this.handleClickCashRegister());

        this.DOMElements.intervalLink.addEventListener('click', this.handleClickCashRegister());

        /** Cierre de caja */
        this.DOMElements.acceptCashRegisterCloseBtn.addEventListener('click', this.handleClickAcceptCashRegisterBtn())
    }
}