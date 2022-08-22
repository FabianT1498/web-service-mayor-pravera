import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

// import { decimalInputs } from '_utilities/decimalInput';

// import { formatAmount } from '_utilities/mathUtilities'

// import { timerDelay } from '_utilities/timerDelay'

// import { storeBillPayable } from '_services/bill-payable';

// import { SIGN } from '_constants/currencies';

export default {
    DOMElements: {
        endDatePicker: document.querySelector('#endDatePicker'),
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        billsPayableTBody: document.querySelector('#billsPayableTBody'),
        billPayableAlert: document.querySelector('#bill-payable-alert'),
    },
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
    showTasaMessage(){
        this.DOMElements.billPayableAlert.classList.remove('hidden', 'opacity-0')
        setTimeout(() => {
            this.DOMElements.billPayableAlert.classList.add('hidden', 'opacity-0')
        }, 5000)
    },
    getBillPayableData(event){
        let row = event.target.closest('tr')
        let numeroD = row.getAttribute('data-numeroD')
        let codProv = row.getAttribute('data-prov');
        let billType = this.DOMElements.formFilter.querySelector('#billType').value;
        let tasa = formatAmount(row.querySelector('input[data-bill=tasa]').value);
        let amount = formatAmount(row.querySelector('td[data-bill=montoTotal]').innerHTML);
        let isDollar = row.querySelector('input[data-bill=isDollar]').value;
       
        return {numeroD, codProv, billType, tasa, amount, isDollar}
    },
    handleDollarCheckClicked: function(event){
        let data = this.getBillPayableData(event);

        if (data.tasa === 0){
            this.showTasaMessage()
        } else {
            event.target.value = event.target.value === '0' ? '1' : '0'
            data.isDollar = event.target.value;

            this.submitBillPayable(data);
        }
    },
    handleClick: function(){
        return (event) => {
            const target = event.target.closest('input');

            if (target){
                const isDolarCheck = target.getAttribute('data-bill');

                if (isDolarCheck && isDolarCheck === "isDollar"){
                    this.handleDollarCheckClicked(event)
                }
            }
        }
    },
    keyEventsOnTBodyHandlerWrapper: function(){
        return (event) => {
            const target = event.target.closest('input');

            if (target){
                const tasaInput = target.getAttribute('data-bill');

                if (tasaInput && tasaInput === "tasa"){
                    let row = event.target.closest('tr')
                    let inputIsDollar = row.querySelector('input[data-bill=isDollar]');
                    let tasa = formatAmount(target.value);
                    let res = null;

                    if (tasa === 0) {
                        inputIsDollar.onclick = function(){ return false }
                        res = this.submitBillPayableCb(true)
                        
                    } else {
                        inputIsDollar.onclick = function(){ return true }
                        let data = this.getBillPayableData(event);
                       
                        res = this.submitBillPayableCb(false, data)
                    }
                }
            }
        }
    },
    changeAmountSign(parentEl, selector, isDollar){
        let targetEl = parentEl.querySelector(selector)
        let innerHtml = targetEl.innerHTML;
        innerHtml = innerHtml.split(' ')
        console.log(isDollar)
        innerHtml[1] = isDollar === '1' ? SIGN['dollar'] : SIGN['bs']
        console.log(innerHtml)
        innerHtml = innerHtml.join(' ')

        console.log(innerHtml);
        targetEl.innerHTML = innerHtml
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

        this.DOMElements.formFilter.querySelector('#isDollar').addEventListener('click', this.handleCheck);

        const tasaInputs = this.DOMElements.billsPayableTBody.querySelectorAll('input[data-bill=tasa]')

        tasaInputs.forEach((el) => {
            decimalInputs['bs'].mask(el)
        })

        this.DOMElements.billsPayableTBody.addEventListener('click', this.handleClick())
        this.DOMElements.billsPayableTBody.addEventListener('keypress', this.keyEventsOnTBodyHandlerWrapper())
        this.DOMElements.billsPayableTBody.addEventListener('keydown', this.keyEventsOnTBodyHandlerWrapper())

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
    submitBillPayable(data){
        storeBillPayable(data).then((res) => {
            let el = this.DOMElements.billsPayableTBody.querySelector(`tr[data-numeroD="${res.data.nro_doc}"][data-prov="${res.data.cod_prov}"]`)

            this.changeAmountSign(el, 'td[data-bill=montoTotal]', res.data.is_dollar)
            this.changeAmountSign(el, 'td[data-bill=montoPagar]', res.data.is_dollar)
        }).catch(err => {
            console.log(err);
        })
    },
    initData(){
        this.submitBillPayableCb = timerDelay(this.submitBillPayable, 3000)
    },
    init(){
        this.initEventListener()
        this.initData();
    }
}
