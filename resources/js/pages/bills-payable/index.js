import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

import { numericInput } from '_utilities/numericInput';

export default {
    DOMElements: {
        endDatePicker: document.querySelector('#endEmissionDatePicker'),
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        billsPayableTBody: document.querySelector('#billsPayableTBody'),
    },
    lastClickedCloseBtnID: -1,
    keypressOnAvailableDaysRange(event, form){
        let targetDays = parseInt(event.target.value);
        let targetID = event.target.getAttribute('id')

        if (isNaN(targetDays)){
            targetDays = event.target.value = 0
        }

        if (targetID === 'minAvailableDays'){
            let maxAvailableDaysEl = form.querySelector('#maxAvailableDays')
            
            if (targetDays > parseInt(maxAvailableDaysEl.value)){
                maxAvailableDaysEl.value = targetDays

                console.log('Maximos dias' + maxAvailableDaysEl.value);
            }
        } else {
            
            let minAvailableDaysEl = form.querySelector('#minAvailableDays')
            
            if (targetDays < parseInt(minAvailableDaysEl.value)){
                minAvailableDaysEl.value = targetDays
            }
        }
    },
    keypressWrapper(){
        let self = this;
        return (event) => {
            let id = event.target.getAttribute('id')
            let key = event.key || event.keyCode

            if (isFinite(key)){
                if ( id === "maxAvailableDays" || id === 'minAvailableDays'){
                    let form = self.DOMElements.formFilter
                    self.keypressOnAvailableDaysRange(event, form)
                }
            }
        }
    },
    keydownWrapper(){
        let self = this;
        return (event) => {
            let key = event.key || event.keyCode
            if (key === 8 || key === 'Backspace'){
                
                let id = event.target.getAttribute('id')
                
                if (id === "maxAvailableDays" || id === 'minAvailableDays'){
                    let form = self.DOMElements.formFilter
                    self.keypressOnAvailableDaysRange(event, form)
                }
            }
        }
    },
    handleCheck: function(event){
        event.target.value = event.target.value === '1' ? '0' : '1';
    },
    initEventListener(){

        // Apply mask to inputs
        // numericInput.mask(this.DOMElements.formFilter.querySelector('#maxAvailableDays'))
        // numericInput.mask(this.DOMElements.formFilter.querySelector('#minAvailableDays'))

        // Initialize date range picker
        Object.assign(Datepicker.locales, es);

        new Datepicker(this.DOMElements.endDatePicker, {
            format: 'dd-mm-yyyy',
            language: 'es',
        });

        // Attach event listener to containers
        this.DOMElements.formFilter.addEventListener('keypress', this.keypressWrapper());
        this.DOMElements.formFilter.addEventListener('keydown', this.keydownWrapper());

        this.DOMElements.formFilter.querySelector('#isDolar').addEventListener('click', this.handleCheck);

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
    }
}