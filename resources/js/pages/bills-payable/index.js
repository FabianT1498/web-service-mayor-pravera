import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';

import es from '@themesberg/tailwind-datepicker/locales/es';

import { decimalInputs } from '_utilities/decimalInput';

import { formatAmount, roundNumber } from '_utilities/mathUtilities'
import { charReplace, isADateFormatDDMMYYYY} from '_utilities/stringUtilities'

import { timerDelay } from '_utilities/timerDelay'

import { storeBillPayable, getProviders, getBillPayable } from '_services/bill-payable';

import { SIGN } from '_constants/currencies';

import BillPayableCollection from '_collections/BillPayableCollection'
import BillPayable from '_models/BillPayable'

import BillPayableScheduleView from '_views/BillPayableScheduleView'
import BillPayableSchedulePresenter from '_presenters/BillPayableSchedulePresenter'

import BillPayableGroupView from '_views/BillPayableGroupView'
import BillPayableGroupPresenter from '_presenters/BillPayableGroupPresenter'

import AutocompletePresenter from '_components/autocomplete/presenter'
import AutocompleteView from '_components/autocomplete/view'

export default {
    DOMElements: {
        endDatePicker: document.querySelector('#endEmissionDatePicker'),
        pagesLinksContainer: document.querySelector('#pages_links_container'),
        formFilter: document.querySelector('#form_filter'),
        pageInput: document.querySelector('#page'),
        billsPayableTBody: document.querySelector('#billsPayableTBody'),
        cleanFormBtn: document.querySelector('#cleanFormBtn'),
        linkBillsPayableContainer: document.querySelector('#linkBillsPayableContainer'),
        billPayableAlert: document.querySelector('#bill-payable-alert'),
    },
    handleCheck: function(event){
        event.target.value = event.target.value === '1' ? '0' : '1';
    },
    showBillPayableMessage(message){
        this.DOMElements.billPayableAlert.classList.remove('hidden', 'opacity-0')
        
        this.DOMElements.billPayableAlert
            .querySelector('#' + this.DOMElements.billPayableAlert.getAttribute('id') + '-message').innerHTML = message
        
        setTimeout(() => {
            this.dismissableAlert.hide()
        }, 5000)
    },
    getBillPayableData(event){
        let row = event.target.closest('tr')
        let numeroD = row.getAttribute('data-numeroD')
        let codProv = row.getAttribute('data-prov');
        let billType = this.DOMElements.formFilter.querySelector('#billType').value;
        let tasa = formatAmount(row.querySelector('input[data-bill=tasa]') ? row.querySelector('input[data-bill=tasa]').value : row.querySelector('a[data-bill=tasa]').innerHTML);
        let amount = formatAmount(row.querySelector('a[data-bill=montoTotal]').innerHTML);
        let isDollar = row.querySelector('input[data-bill=isDollar]').value;
        let provDescrip = row.getAttribute('data-descripProv')
        let fechaE = row.querySelector('a[data-bill=fechaE]').innerHTML

        return {numeroD, codProv, billType, tasa, amount, isDollar, provDescrip, fechaE}
    },
    handleDollarCheckClicked: function(event){
        let data = this.getBillPayableData(event);

        if (data.tasa === 0){
            this.showBillPayableMessage('La tasa no puede ser igual a cero')
        } else {
            event.target.value = event.target.value === '0' ? '1' : '0'
            data.isDollar = event.target.value;

            this.submitBillPayable(data);
        }
    },
    handleClickLinkBillsPayable: function(){
        return (event) => {
            if (this.checkIfAreSameProviders()){
                this.billPayableGroupView.showModal();
                const bill = this.selectedBills.getFirst();
            
                this.billPayableGroupPresenter.setBillPayableProvider({
                    codProv: bill.codProv,
                    provDescrip: bill.provDescrip
                });

                this.billPayableGroupPresenter.setBillsPayable(this.selectedBills.getAll())

                console.log(this.selectedBills.getAll())
                
            } else {

                this.showBillPayableMessage('Las facturas no pertenecen al mismo proveedor.')
                
            }
        }
    },
    handleClick: function(){
        return (event) => {

            const input = event.target.closest('input');
            const modalBtn = event.target.closest('button');

            let dataBill = input && input.getAttribute('data-bill') 
                ? input.getAttribute('data-bill') : 
                (modalBtn && modalBtn.getAttribute('data-bill')
                    ? modalBtn.getAttribute('data-bill') 
                    : null)

            if (dataBill){
                if (dataBill === 'isDollar'){
                    this.handleDollarCheckClicked(event)
                } else if (dataBill === 'select'){
                    
                    if (event.target.value === '0'){
                        let data = this.getBillPayableData(event)

                        let row = event.target.closest('tr')
                        let numeroD = row.getAttribute('data-numeroD')
                        let codProv = row.getAttribute('data-prov')

                        let formattedNumeroD = charReplace(numeroD)
                        let isADate = isADateFormatDDMMYYYY(formattedNumeroD)

                        if (isADate){
                            numeroD = formattedNumeroD;
                        }

                        getBillPayable({numeroD, codProv})
                            .then(res => {

                                if (res.data.length > 0){
                                    let bill = res.data[0];

                                    if (bill.ScheduleID !== null || roundNumber(bill.MontoPagado) > 0){
                                        return false;
                                    } 
                                }
                                
                                event.target.value = '1'
        
                                if (event.target.value === '1'){
                                    this.addBillPayable(data);
                                } else {
                                    this.removeBillPayable(data);
                                }
            
                                if (this.selectedBills.getLength() > 0){
                                    this.showLinkBillsBtn();
                                } else {
                                    this.hideLinkBillsBtn();
                                }
                                
                            })
                            .catch(err => {
                                console.log(err)
                            })
                    } else {
                        event.target.value = '0'
                    }
                } else if (dataBill === 'modalBtn'){
                    let data = this.getBillPayableData(event);

                    this.billPayableSchedulePresenter.setBillPayable(data)
                }
            }
        }
    },
    handleClickWrapperCleanFormBtn: function(){
        return (event) => {
            this.DOMElements.formFilter.querySelector('#endEmissionDatePicker').value = ''
            this.DOMElements.formFilter.querySelector('#nroDoc').value = ''
            this.DOMElements.formFilter.querySelector('#provider_search_hidden').value = ''
            this.DOMElements.formFilter.querySelector('#provider_search_input').value = ''
            this.DOMElements.formFilter.querySelector('#isDollar').value = '0'
            this.DOMElements.formFilter.querySelector('#billType').value = ''
            this.DOMElements.formFilter.submit()
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
    addBillPayable(data){
        let billPayable = new BillPayable(
            data.numeroD,
            data.codProv, 
            data.provDescrip,
            data.billType,
            data.tasa, 
            data.amount,
            data.isDollar,
            data.fechaE
        );
        
        this.selectedBills.pushElement(billPayable)
    },
    removeBillPayable(data){
        this.selectedBills.removeElementByBillPayableData(data.numeroD, data.codProv)
    },
    showLinkBillsBtn(){
        this.DOMElements.linkBillsPayableContainer.classList.remove('hidden')
    },
    hideLinkBillsBtn(){
        this.DOMElements.linkBillsPayableContainer.classList.add('hidden')
    },
    checkIfAreSameProviders(){
        let firstBill = this.selectedBills.getFirst()
        let areSame = true;

        this.selectedBills.getAll().forEach((item) => {
            if (item.codProv !== firstBill.codProv){
                areSame = false
                return false;
            }
        })

        return areSame;
    },
    initEventListener(){

        // options object
        const dissmisableAlertOptions = {
            transition: 'transition-opacity',
            duration: 1000,
            timing: 'ease-out',
        };

        this.dismissableAlert = new Dismiss(this.DOMElements.billPayableAlert, dissmisableAlertOptions);

        // Initialize date range picker
        Object.assign(Datepicker.locales, es);

        new Datepicker(this.DOMElements.endDatePicker, {
            format: 'dd-mm-yyyy',
            language: 'es',
        });

        const tasaInputs = this.DOMElements.billsPayableTBody.querySelectorAll('input[data-bill=tasa]')

        tasaInputs.forEach((el) => {
            decimalInputs['bs'].mask(el)
        })

        this.DOMElements.billsPayableTBody.addEventListener('click', this.handleClick())
        this.DOMElements.billsPayableTBody.addEventListener('keypress', this.keyEventsOnTBodyHandlerWrapper())
        this.DOMElements.billsPayableTBody.addEventListener('keydown', this.keyEventsOnTBodyHandlerWrapper())

        this.DOMElements.cleanFormBtn.addEventListener('click', this.handleClickWrapperCleanFormBtn())

        this.DOMElements.linkBillsPayableContainer.addEventListener('click', this.handleClickLinkBillsPayable());

        // this.billPayableGroupModal = new Modal(this.DOMElements.billPayableGroupModal);

        // this.DOMElements.billPayableGroupModalCloseBtn.addEventListener('click', this.handleClickCloseBillPayableGroup())

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

            this.changeAmountSign(el, 'a[data-bill=montoTotal]', res.data.is_dollar)
            // this.changeAmountSign(el, 'a[data-bill=montoPagar]', res.data.is_dollar)
            
        }).catch(err => {
            console.log(err);
        })
    },
    initData(){
        this.submitBillPayableCb = timerDelay(this.submitBillPayable, 3000)
        this.selectedBills = new BillPayableCollection();  
    },
    init(){
        this.initEventListener()
        this.initData();

        let billPayableScheduleContainer = document.querySelector('#bill_payable_schedules')
        this.billPayableSchedulePresenter = new BillPayableSchedulePresenter();
        this.billPayableScheduleView = new BillPayableScheduleView(this.billPayableSchedulePresenter);
        this.billPayableScheduleView.init(billPayableScheduleContainer)

        this.billPayableGroupPresenter = new BillPayableGroupPresenter()
        this.billPayableGroupView = new BillPayableGroupView(this.billPayableGroupPresenter);
        this.billPayableGroupView.init(document.querySelector('#billPayableGroupModal'))

        let providerSearchBoxElement =  document.querySelector('#provider_search');
        let providerSearchBoxPresenter = new AutocompletePresenter(getProviders);
        let providerSearchBoxView = new AutocompleteView(providerSearchBoxPresenter);
        providerSearchBoxView.init(providerSearchBoxElement)
    }
}
