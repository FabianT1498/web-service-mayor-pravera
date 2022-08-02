import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

import { decimalInputs } from '_utilities/decimalInput';

import { formatAmount, roundNumber } from '_utilities/mathUtilities'

export default {
    DOMElements: {
        endDatePicker: document.querySelector('#endEmissionDatePicker'),
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        billsPayableTBody: document.querySelector('#billsPayableTBody'),
        billPayableAlert: document.querySelector('#bill-payable-alert'),
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
    handleClick: function(){
        let self = this;
        return function(event){
            const target = event.target.closest('input');

            if (target){
                const isDolarCheck = target.getAttribute('data-bill');

                if (isDolarCheck && isDolarCheck === "isDolar"){
                    let row = event.target.closest('tr')

                    let inputTasa = row.querySelector('input[data-bill=tasa]');
                    let tasaValue = formatAmount(inputTasa.value);

                    if (tasaValue === 0){
                        self.DOMElements.billPayableAlert.classList.remove('hidden', 'opacity-0')

                        setTimeout(function(){
                            self.DOMElements.billPayableAlert.classList.add('hidden', 'opacity-0')
                        }, 5000)
                        
                    }
                }
            }
        }
    },
    handleKeypressOnTBody: function(){
        let self = this;
        return function(event){
            const target = event.target.closest('input');

            if (target){
                const tasaInput = target.getAttribute('data-bill');

                if (tasaInput && tasaInput === "tasa"){
                    let row = event.target.closest('tr')
                    let inputIsDolar = row.querySelector('input[data-bill=isDolar]');
                    let tasaValue = formatAmount(target.value);
                    
                    inputIsDolar.onclick = function(){ return tasaValue === 0 ? false : true}
                }
            }
        }
    },
    handleKeydownOnTBody: function(){
        let self = this;
        return function(event){
            const target = event.target.closest('input');

            if (target){
                const tasaInput = target.getAttribute('data-bill');

                if (event.key === 8 || event.key === 'Backspace'){
                    if (tasaInput && tasaInput === "tasa"){
                        let row = event.target.closest('tr')
                        let inputIsDolar = row.querySelector('input[data-bill=isDolar]');
                        let tasaValue = formatAmount(target.value);
                        inputIsDolar.onclick = function(){ return tasaValue === 0 ? false : true}
                        
                    }
                }

            }
        }
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

        const tasaInputs = this.DOMElements.billsPayableTBody.querySelectorAll('input[data-bill=tasa]')

        tasaInputs.forEach((el) => {
            decimalInputs['bs'].mask(el)
        })

        this.DOMElements.billsPayableTBody.addEventListener('click', this.handleClick())
        this.DOMElements.billsPayableTBody.addEventListener('keypress', this.handleKeypressOnTBody())
        this.DOMElements.billsPayableTBody.addEventListener('keydown', this.handleKeydownOnTBody())

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