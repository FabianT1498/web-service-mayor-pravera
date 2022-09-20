import { CURRENCIES, SIGN as CURRENCY_SIGN_MAP} from '_constants/currencies';
import { PAYMENT_METHODS } from '_constants/paymentMethods';
import { PAYMENT_CODES, PAYMENT_CURRENCIES} from '_constants/paymentCodes';
import { AMOUNT_COLORS } from '_constants/colors';

import MoneyRecordModalView from '_views/MoneyRecordModalView'
import MoneyRecordModalPresenter from '_presenters/MoneyRecordModalPresenter'
import MoneyRecordTable from '_components/money-record-table/MoneyRecordTable'

import ForeignMoneyRecordModalView from '_views/ForeignMoneyRecordModalView'
import ForeignMoneyRecordModalPresenter from '_presenters/ForeignMoneyRecordModalPresenter'
import ForeignMoneyRecordTable from '_components/money-record-table/ForeignMoneyRecordTable'

import DenominationModalPresenter from '_presenters/DenominationModalPresenter'
import DenominationModalView from '_views/DenominationModalView'

import SalePointModalPresenter from '_presenters/SalePointModalPresenter'
import SalePointModalView from '_views/SalePointModalView'

import CashRegisterDataPresenter from '_presenters/CashRegisterDataPresenter'
import CashRegisterDataView from '_views/CashRegisterDataView'

import NotesView from '_views/NotesView'
import NotesPresenter from '_presenters/NotesPresenter'

import {decimalInputs} from '_utilities/decimalInput';
import { numericInput } from '_utilities/numericInput';
import { roundNumber, formatAmount } from '_utilities/mathUtilities'

import MoneyRecord from '_models/moneyRecord'
import DenominationRecord from '_models/DenominationRecord'
import PointSaleRecord from '_models/PointSaleRecord'
import Bank from '_models/Bank';
import Note from '_models/Note';

import {default as modalBehavior} from '_app/base/modalBehavior'

import { getTotalsToCashRegisterUserSaint, getTotalsToCashRegisterUser,
    getMoneyBackToCashRegisterUserSaint } from '_services/cash-register';
import { getDollarExchangeToDate } from '_services/dollar-exchange';

import { boundStoreDollarExchange } from '_store/action'

export default {
    totalInputDOMS: {
        liquidMoneyDollar: document.querySelector('#total_dollar_cash_input'),
        pagoMovilBs: document.querySelector('#total_pago_movil_bs_input'),
        denominationsBs: document.querySelector('#total_bs_denominations_input'),
        denominationsDollar: document.querySelector('#total_dollar_denominations_input'),
        zelleDollar: document.querySelector('#total_zelle_input'),
        pointSaleDollar: document.querySelector('#total_point_sale_dollar_input'),
        pointSaleBs: document.querySelector('#total_point_sale_bs_input'),
    },
    totalDOMS: {
        liquidMoneyDollar: document.querySelector('#total_dollar_cash'),
        pagoMovilBs: document.querySelector('#total_pago_movil_bs'),
        denominationsBs: document.querySelector('#total_bs_denominations'),
        denominationsDollar: document.querySelector('#total_dollar_denominations'),
        zelleDollar: document.querySelector('#total_zelle'),
        pointSaleDollar: document.querySelector('#total_point_sale_dollar'),
        pointSaleBs: document.querySelector('#total_point_sale_bs'),
    },
    totalSaintDOMS: {
        liquidMoneyBs: document.querySelector('#total_bs_cash_saint'),
        liquidMoneyDollar: document.querySelectorAll('.total_dollar_cash_saint'),
        pointSaleDollar: document.querySelector('#total_point_sale_dollar_saint'),
        pointSaleBs: document.querySelector('#total_point_sale_bs_saint'),
        pagoMovilBs: document.querySelector('#total_pago_movil_bs_saint'),
        zelleDollar: document.querySelector('#total_zelle_saint'),
    },
    vueltosSaintDOMS: {
        liquidMoneyDollar: document.querySelector('#vuelto_dollar_cash_saint'),
        liquidMoneyBs: document.querySelector('#vuelto_bs_cash_saint'),
        pagoMovilBs: document.querySelector('#vuelto_pago_movil_bs_saint'),
    },
    totalDiffDOMS: {
        liquidMoneyBs: document.querySelector('#total_bs_cash_diff'),
        liquidMoneyDollar: document.querySelector('#total_dollar_cash_diff'),
        liquidMoneyDollarDenomination: document.querySelector('#total_dollar_cash_denomination_diff'),
        pagoMovilBs: document.querySelector('#total_pago_movil_bs_diff'),
        pointSaleDollar: document.querySelector('#total_point_sale_dollar_diff'),
        pointSaleBs: document.querySelector('#total_point_sale_bs_diff'),
        zelleDollar: document.querySelector('#total_zelle_diff'),
    },
    propNameToDiffTotalMethod: {
        liquidMoneyBs: 'setTotalBsCashDiff',
        liquidMoneyDollar: 'setTotalDollarCashDiff',
        pagoMovilBs: 'setTotalPagoMovilBsDiff',
        pointSaleDollar: 'setTotalPointSaleDollarDiff',
        pointSaleBs: 'setTotalPointSaleBsDiff',
        zelleDollar: 'setTotalZelleDiff',
    },
    proxyTotalSaint: null,
    proxy: null,
    proxyVueltosSaint: null,
    setTotalNotesCount(count){
        document.querySelector('#notes-count').innerHTML = count
    },
    setTotalLiquidMoneyBs(total){
        this.proxy.liquidMoneyBs = total
        this.setTotalBsCashDiff();
    },
    setTotalLiquidMoneyDollar(total){
        this.proxy.liquidMoneyDollar = total
        this.setTotalDollarCashDiff();
    },
    setTotalPagoMovilBs(total){
        this.proxy.pagoMovilBs = total
        this.setTotalPagoMovilBsDiff();
    },
    setTotalDenominationBs(total){
        this.proxy.denominationsBs = total
        this.setTotalBsCashDiff();        
    },
    setTotalDenominationDollar(total){
        this.proxy.denominationsDollar = total
        this.setTotalDollarCashDenominationDiff();
    },
    setTotalZelleDollar(total){
        this.proxy.zelleDollar = total
        this.setTotalZelleDiff();
    },
    setTotalPointSaleBs(total){
        this.proxy.pointSaleBs = total
        this.setTotalPointSaleBsDiff();
    },
    setTotalPointSaleDollar(total){
        this.proxy.pointSaleDollar = total
        this.setTotalPointSaleDollarDiff();
    },
    setTotalSaintDOMS(totals = null){
        if (!totals){
            Object.keys(this.proxyTotalSaint).forEach(el => {
                this.proxyTotalSaint[el] = 0;
                this[this.propNameToDiffTotalMethod[el]].call(this)
            })

            this.setTotalDollarCashDenominationDiff();
        } else {
            // Liquid Money Payment Amounts
            if (totals.totals_from_safact.length > 0){
                let totalsFromSafact = totals.totals_from_safact[0];

                this.proxyTotalSaint['liquidMoneyBs'] = parseFloat(totalsFromSafact.bolivares);
                this.setTotalBsCashDiff(this);

                this.proxyTotalSaint['liquidMoneyDollar'] = parseFloat(totalsFromSafact.dolares);
                this.setTotalDollarCashDiff(this);
                this.setTotalDollarCashDenominationDiff(this);
            }

            if (totals.totals_e_payments.length > 0){

                let totalsEPayments = totals.totals_e_payments;
        
                // E-Payment Amounts
                totalsEPayments.forEach(el => {
                        
                        if (PAYMENT_CURRENCIES[el.CodPago] === 'bs'){
                            if (el.CodPago === '01' || el.CodPago === '02'
                                    || el.CodPago === '03' || el.CodPago === '04'){
                                this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]] += parseFloat(el.totalBs)
                            } else {
                                this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]] = parseFloat(el.totalBs)
                            }
                        } else if (PAYMENT_CURRENCIES[el.CodPago] === 'dollar'){
                            this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]] = parseFloat(el.totalDollar)
                        }
                        
                        this[this.propNameToDiffTotalMethod[PAYMENT_CODES[el.CodPago]]].call(this)
                    
                    });
            }
        }
    },
    setSaintVueltosDOMS(data = null){
        if (!data){
            Object.keys(this.proxyVueltosSaint).forEach(el => {
                this.proxyVueltosSaint[el] = 0;
            })
        } else if (data.length > 0){
            let vuelto = data[0]
            this.proxyVueltosSaint['liquidMoneyDollar'] = parseFloat(vuelto.MontoDivEfect);
            this.proxyVueltosSaint['liquidMoneyBs'] = parseFloat(vuelto.MontoBsEfect);
            this.proxyVueltosSaint['pagoMovilBs'] = parseFloat(vuelto.MontoDivPM);
        }
        
        this.setTotalDollarCashDenominationDiff(this);
        this.setTotalBsCashDiff(this);

    },
    setTotalDollarCashDiff(){
        let diff = this.proxy.liquidMoneyDollar - this.proxyTotalSaint.liquidMoneyDollar;
        let color = this.getAmountColor(roundNumber(diff));
        this.totalDiffDOMS.liquidMoneyDollar.className = '';
        if (color !== ''){
            this.totalDiffDOMS.liquidMoneyDollar.classList.add(color);
        }
        this.totalDiffDOMS.liquidMoneyDollar.innerHTML = roundNumber(diff).format();
    },
    setTotalDollarCashDenominationDiff(){
        let diff = (this.proxy.denominationsDollar - this.proxyTotalSaint.liquidMoneyDollar) - (this.proxyVueltosSaint.liquidMoneyDollar + this.proxyVueltosSaint.pagoMovilBs);
        let color = this.getAmountColor(roundNumber(diff));
        
        this.totalDiffDOMS.liquidMoneyDollarDenomination.className = '';
        if (color !== ''){
            this.totalDiffDOMS.liquidMoneyDollarDenomination.classList.add(color);
        } 
          
        this.totalDiffDOMS.liquidMoneyDollarDenomination.innerHTML = roundNumber(diff).format();
    },
    setTotalBsCashDiff(){
        let diff = this.proxy.denominationsBs - (this.proxyTotalSaint.liquidMoneyBs - this.proxyVueltosSaint.liquidMoneyBs);
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.liquidMoneyBs.className = '';
        if (color !== ''){
            this.totalDiffDOMS.liquidMoneyBs.classList.add(color);
        }   
        this.totalDiffDOMS.liquidMoneyBs.innerHTML = roundNumber(diff).format();
    },
    setTotalPointSaleBsDiff(){
        let diff = this.proxy.pointSaleBs - this.proxyTotalSaint.pointSaleBs;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.pointSaleBs.className = '';
        if (color !== ''){
            this.totalDiffDOMS.pointSaleBs.classList.add(color);
        }
        this.totalDiffDOMS.pointSaleBs.innerHTML = roundNumber(diff).format();
    },
    setTotalPointSaleDollarDiff(){
        let diff = this.proxy.pointSaleDollar - this.proxyTotalSaint.pointSaleDollar;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.pointSaleDollar.className = '';
        if (color !== ''){
            this.totalDiffDOMS.pointSaleDollar.classList.add(color);
        }        
        this.totalDiffDOMS.pointSaleDollar.innerHTML = roundNumber(diff).format();
    },
    setTotalZelleDiff(){
        let diff = this.proxy.zelleDollar - this.proxyTotalSaint.zelleDollar;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.zelleDollar.className = '';
        if (color !== ''){
            this.totalDiffDOMS.zelleDollar.classList.add(color);
        }        
        this.totalDiffDOMS.zelleDollar.innerHTML = roundNumber(diff).format();
    },
    setTotalPagoMovilBsDiff(){
        let diff = this.proxy.pagoMovilBs - this.proxyTotalSaint.pagoMovilBs;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.pagoMovilBs.className = '';
        if (color !== ''){
            this.totalDiffDOMS.pagoMovilBs.classList.add(color);
        }        
        this.totalDiffDOMS.pagoMovilBs.innerHTML = roundNumber(diff).format();
    },
    setPropWrapper(fn){
        return fn.bind(this)
    },
    getAmountColor(amount){
        if (amount > 0){
            return AMOUNT_COLORS.POSITIVE;
        } else if (amount < 0){
            return AMOUNT_COLORS.NEGATIVE;
        }

        return '';
    },
    initData(){
        let handlerInputDOMS = (self, key, value) => {
            self.totalInputDOMS[key].value = value;
            self.totalDOMS[key].innerHTML = value.format();
        }

        let handlerTotalSaintDOMS = (self, key, value) => {
            if (NodeList.prototype.isPrototypeOf(self.totalSaintDOMS[key])){
                self.totalSaintDOMS[key].forEach(el => {
                    el.innerHTML = roundNumber(value).format();
                })
            } else {
                self.totalSaintDOMS[key].innerHTML = roundNumber(value).format();
            }
        }

        let handlerVueltosSaintDOMS = (self, key, value) => {
            self.vueltosSaintDOMS[key].innerHTML = roundNumber(value).format()
        }

        let handlerWrapper = (fn) => {
            let self = this;
            return {
                set: function(target, key, value) {
                    target[key] = value;
                    fn(self, key, value)
                    return true;
                },
            }
        }

        let totalInputkeys = Object.keys(this.totalInputDOMS).reduce((obj, key) => {
            obj[key] = 0;
            return obj;
        }, {})

        let totalSaintkeys = Object.keys(this.totalSaintDOMS).reduce((obj, key) => {
            obj[key] = 0;
            return obj;
        }, {})

        let vueltosSaintKeys =  Object.keys(this.vueltosSaintDOMS).reduce((obj, key) => {
            obj[key] = 0;
            return obj;
        }, {}) 

        this.proxy = new Proxy(totalInputkeys, handlerWrapper(handlerInputDOMS))
        this.proxyTotalSaint = new Proxy(totalSaintkeys, handlerWrapper(handlerTotalSaintDOMS))        
        this.proxyVueltosSaint = new Proxy(vueltosSaintKeys, handlerWrapper(handlerVueltosSaintDOMS))     

    
    },
    initEventListeners(){
        // // Cash register modal total input DOMs
        // decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.liquidMoneyBs);
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.liquidMoneyDollar);

        // // Pago movil modal total input DOMs
        decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.pagoMovilBs);
        
        // // Denomination modal total input DOMs
        decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.denominationsBs);
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.denominationsDollar);

        // // Zelle total input DOMs
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.zelleDollar);

        // Point sale input DOMS
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.pointSaleDollar);
        decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.pointSaleBs);

        let form = document.querySelector('#form')
        
        form.addEventListener('submit', (e) => {
            form.querySelector('button[type="submit"]').disabled = true;

            let elements = form.elements;
            for (let i = 0; i < elements.length; i++) {
                elements[i].readOnly = true;
            }
        })
    },
    fetchInitialData(){
        let id = document.querySelector('#id').value;

        let date = '';
        let user = ''
       
        getTotalsToCashRegisterUser(id)
            .then(res => {
                if ([201, 200].includes(res.status)){
                    let data = res.data.data;
                    this.setTotalDenominationBs(roundNumber(parseFloat(data.total_bs_denominations)));
                    this.setTotalDenominationDollar(roundNumber(parseFloat(data.total_dollar_denominations)))
                    this.setTotalLiquidMoneyDollar(roundNumber(parseFloat(data.total_dollar_cash)));
                    this.setTotalPointSaleBs(roundNumber(parseFloat(data.total_point_sale_bs)));
                    this.setTotalPagoMovilBs(roundNumber(parseFloat(data.total_pago_movil_bs)));
                    this.setTotalPointSaleDollar(roundNumber(parseFloat(data.total_point_sale_dollar)));
                    this.setTotalZelleDollar(roundNumber(parseFloat(data.total_zelle)));
                 
                    date = data.date;
                    user = data.cod_usua
               
                    const params = {
                        date,
                        cashRegisterUser: user
                    };
                    

                    return getTotalsToCashRegisterUserSaint(params);
                }
            })
            .then(res => {
              
                if ([201, 200].includes(res.status)){
                    let data = res.data.data;
                    
                    this.setTotalSaintDOMS(data)

                    return getMoneyBackToCashRegisterUserSaint({date, cashRegisterUser: user})
                }
            })
            .then(res => {
				if ([201, 200].includes(res.status)){
					let data = res.data.data;

					this.setSaintVueltosDOMS(data);

                    return getDollarExchangeToDate(date)
				}
			})
            .then(res => {
       
                if ([201, 200].includes(res.status)){
                    let data = res.data.data;
                 
                    let dollarExchange = {
                        value: data.bs_exchange,
                        createdAt: data.created_at
                    }

                    boundStoreDollarExchange(dollarExchange)
                }
            })
            .catch(err => {
                console.log(err);
            })
    },
    init(){
        this.initData();
        this.initEventListeners();
        this.fetchInitialData();

        let cashRegisterContainer = document.querySelector('#cash_register_data')
        let cashRegisterUser = cashRegisterContainer.querySelector('#cash_register_id').value;
        let casgRegisterDate = cashRegisterContainer.querySelector('#date').value;
        let cashRegisterDataPresenter = new CashRegisterDataPresenter(this.setPropWrapper(this.setTotalSaintDOMS),
            this.setPropWrapper(this.setSaintVueltosDOMS), casgRegisterDate, cashRegisterUser);
        let cashRegisterDataView = new CashRegisterDataView(cashRegisterDataPresenter);
        cashRegisterDataView.init(cashRegisterContainer)

        // Notes
        let notesContainer = document.querySelector('#notes-modal')
        let notesElements = notesContainer.querySelector('#notes-modal-container').children;
        let notesRecords = Array.prototype.reduce.call(notesElements, function(acc, el, key){
            let title = el.querySelector('input').value;
            let description = el.querySelector('textarea').value;
            
            if (description !== '' && key > 0){
                acc.push(new Note(title, description, key - 1))
            }

            return acc;
        }, []);

        let notesPresenter = new NotesPresenter(this.setPropWrapper(this.setTotalNotesCount), notesRecords);
        let notesView = new NotesView(notesPresenter);
        notesView.init(notesContainer)

        // Pago movil bs
        let pagoMovilBsModal = document.querySelector('#pago_movil_record');
        let pagoMovilBsRecordsElements = pagoMovilBsModal.querySelector('tbody').children;
        let pagoMovilBsRecords = Array.prototype.map.call(pagoMovilBsRecordsElements, function(el, key){
            let input = el.querySelector('input[id^="pago_movil_record_"]');
            let amount = roundNumber(parseFloat(input.value));
            decimalInputs[CURRENCIES.BOLIVAR].mask(input);
            return new MoneyRecord(amount,  CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH, key);
        });
        let pagoMovilBsPresenter = new MoneyRecordModalPresenter(
            CURRENCIES.BOLIVAR,
            PAYMENT_METHODS.CASH,
            this.setPropWrapper(this.setTotalPagoMovilBs),
            pagoMovilBsRecords
        );
        let pagoMovilBsView = new MoneyRecordModalView(pagoMovilBsPresenter);
        let pagoMovilBsRecordTable = new MoneyRecordTable()
        pagoMovilBsView.init(pagoMovilBsModal, 'pago_movil_record', pagoMovilBsRecordTable)

        // Cash records dollar
        let cashDollarRecordModal = document.querySelector('#dollar_cash_record');
        let cashDollarRecordsElements = cashDollarRecordModal.querySelector('tbody').children;
        let cashDollarRecords = Array.prototype.map.call(cashDollarRecordsElements, function(el, key){
            let input = el.querySelector('input[id^="dollar_cash_record_"]');
            let amount = roundNumber(parseFloat(input.value));
            decimalInputs[CURRENCIES.DOLLAR].mask(input);
            return new MoneyRecord(amount,  CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH, key);
        });
        let dollarRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(
            CURRENCIES.DOLLAR,
            PAYMENT_METHODS.CASH,
            this.setPropWrapper(this.setTotalLiquidMoneyDollar),
            cashDollarRecords
        );
        let dollarRecordMoneyView = new ForeignMoneyRecordModalView(dollarRecordMoneyPresenter);
        let dollarRecordTable = new ForeignMoneyRecordTable()
        dollarRecordMoneyView.init(cashDollarRecordModal, 'dollar_cash_record', dollarRecordTable)

        // Bs denomination records
        let bsDenominationsModal = document.querySelector('#bs_denominations_record');
        let bsDenominationRecordsElements = bsDenominationsModal.querySelector('tbody').children;
        let bsDenominationRecords = Array.prototype.map.call(bsDenominationRecordsElements, function(el, key){
            let input = el.querySelector('input');
            numericInput.mask(input);
            let amount = input.value !== '' ? parseInt(input.value) : 0;
            let denomination = parseFloat(input.getAttribute('data-denomination'));
            let total = Math.round(((denomination * amount) + Number.EPSILON) * 100) / 100
            return new DenominationRecord(CURRENCIES.BOLIVAR, denomination, total, amount, key);
        });

        let bolivarDenominationModalPresenter = new DenominationModalPresenter(
            CURRENCIES.BOLIVAR,
            PAYMENT_METHODS.CASH,
            this.setPropWrapper(this.setTotalDenominationBs),
            bsDenominationRecords
        )
        let bolivarDenominationModalView = new DenominationModalView(bolivarDenominationModalPresenter);
        bolivarDenominationModalView.init(bsDenominationsModal, 'bs_denominations_record');

        // Dollar denomination records
        let dollarDenominationsModal = document.querySelector('#dollar_denominations_record');
        let dollarDenominationRecordsElements = dollarDenominationsModal.querySelector('tbody').children;
        let dollarDenominationRecords = Array.prototype.map.call(dollarDenominationRecordsElements, function(el, key){
            let input = el.querySelector('input');
            numericInput.mask(input);
            let amount = input.value !== '' ? parseInt(input.value) : 0;
            let denomination = parseFloat(input.getAttribute('data-denomination'));
            let total = Math.round(((denomination * amount) + Number.EPSILON) * 100) / 100
            return new DenominationRecord(CURRENCIES.DOLLAR, denomination, total, amount, key);
        });
        let dollarDenominationModalPresenter = new DenominationModalPresenter(
            CURRENCIES.DOLLAR,
            PAYMENT_METHODS.CASH,
            this.setPropWrapper(this.setTotalDenominationDollar),
            dollarDenominationRecords
        )
        let dollarDenominationModalView = new DenominationModalView(dollarDenominationModalPresenter);
        dollarDenominationModalView.init(dollarDenominationsModal, 'dollar_denominations_record');

        // Point of sale bs records
        let salePointModal = document.querySelector('#point_sale_bs');
        let pointSaleBsRecordsElements = salePointModal.querySelector('tbody').children;
        let pointSaleBsRecords = {'credit' : [], 'debit': [], 'amex' : [], 'todoticket': [],
                'bank': [], 'availableBanks': []}

        if (pointSaleBsRecordsElements && pointSaleBsRecordsElements.length > 0){
            // Get the availables banks
            let bankSelectEl = salePointModal.querySelector('tbody tr select[name^="point_sale_bs_bank"]');
            if (bankSelectEl && bankSelectEl.options.length > 1){
                for (let i = 1; i < bankSelectEl.options.length; i++){
                    pointSaleBsRecords['availableBanks']
                        .push(bankSelectEl.options[i].value);
                }
            }

            pointSaleBsRecords = Array.prototype
                .reduce.call(pointSaleBsRecordsElements,
                    function(obj, curr, index){
                        let bank = curr.querySelector('select[name^="point_sale_bs_bank"]').value;
                        let bankObj = new Bank(bank, index)
                        let creditInput = curr.querySelector('input[id^="point_sale_bs_credit_"]');
                        let debitInput = curr.querySelector('input[id^="point_sale_bs_debit_"]');
                        let amexInput = curr.querySelector('input[id^="point_sale_bs_amex_"]');
                        let todoticketInput = curr.querySelector('input[id^="point_sale_bs_todoticket_"]');
                        let credit = roundNumber(parseFloat(creditInput.value));
                        let debit = roundNumber(parseFloat(debitInput.value));
                        let amex = roundNumber(parseFloat(amexInput.value));
                        let todoticket = roundNumber(parseFloat(todoticketInput.value));
                        decimalInputs[CURRENCIES.BOLIVAR].mask(creditInput);
                        decimalInputs[CURRENCIES.BOLIVAR].mask(debitInput);
                        decimalInputs[CURRENCIES.BOLIVAR].mask(amexInput);
                        decimalInputs[CURRENCIES.BOLIVAR].mask(todoticketInput);
                        obj['credit'].push(new PointSaleRecord(CURRENCIES.BOLIVAR, credit, bankObj, index));
                        obj['debit'].push(new PointSaleRecord(CURRENCIES.BOLIVAR, debit, bankObj, index));
                        obj['amex'].push(new PointSaleRecord(CURRENCIES.BOLIVAR, amex, bankObj, index));
                        obj['todoticket'].push(new PointSaleRecord(CURRENCIES.BOLIVAR, todoticket, bankObj, index));
                        obj['bank'].push(bankObj);
                        return obj;
                    }, pointSaleBsRecords);
        }

        let salePointModalPresenter = new SalePointModalPresenter(
            CURRENCIES.BOLIVAR,
            this.setPropWrapper(this.setTotalPointSaleBs),
            pointSaleBsRecords
        )
        let salePointModalView = new SalePointModalView(salePointModalPresenter);
        salePointModalView.init(salePointModal, 'point_sale_bs');

        // Zelle records
        let zelleRecordModal = document.querySelector('#zelle_record');
        let zelleRecordsElements = zelleRecordModal.querySelector('tbody').children;
        let zelleRecords = Array.prototype.map.call(zelleRecordsElements, function(el, key){
            let input = el.querySelector('input[id^="zelle_record_"]');
            let amount = roundNumber(parseFloat(input.value));
            decimalInputs[CURRENCIES.DOLLAR].mask(input);
            return new MoneyRecord(amount,  CURRENCIES.DOLLAR, PAYMENT_METHODS.ZELLE, key);
        });
        let zelleRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(
            CURRENCIES.DOLLAR,
            PAYMENT_METHODS.ZELLE,
            this.setPropWrapper(this.setTotalZelleDollar),
            zelleRecords
        );
        let zelleRecordMoneyView = new ForeignMoneyRecordModalView(zelleRecordMoneyPresenter);
        let zelleRecordTable = new ForeignMoneyRecordTable()
        zelleRecordMoneyView.init(zelleRecordModal, 'zelle_record', zelleRecordTable)
        
        // Point of sale dollar records
        let pointSaleDollarModal = document.querySelector('#point_sale_dollar_record');
        let pointSaleDollarRecordsElements = pointSaleDollarModal.querySelector('tbody').children;
        let pointSaleDollarRecords = Array.prototype.map.call(pointSaleDollarRecordsElements, function(el, key){
            let input = el.querySelector('input[id^="point_sale_dollar_record_"]');
            let amount = roundNumber(parseFloat(input.value));
            decimalInputs[CURRENCIES.DOLLAR].mask(input);
            return new MoneyRecord(amount,  CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH, key);
        });
        let pointSaleDollarPresenter = new ForeignMoneyRecordModalPresenter(
            CURRENCIES.DOLLAR,
            PAYMENT_METHODS.CASH,
            this.setPropWrapper(this.setTotalPointSaleDollar),
            pointSaleDollarRecords
        );
        
        let pointSaleDollarView = new ForeignMoneyRecordModalView(pointSaleDollarPresenter);
        let pointSaleDollarRecordTable = new ForeignMoneyRecordTable()
        pointSaleDollarView.init(pointSaleDollarModal, 'point_sale_dollar_record', pointSaleDollarRecordTable)

    }
}
