import { CURRENCIES, SIGN as CURRENCY_SIGN_MAP} from '_constants/currencies';
import { PAYMENT_METHODS } from '_constants/paymentMethods';
import { PAYMENT_CODES, TYPE_BILLS} from '_constants/paymentCodes';
import { AMOUNT_COLORS } from '_constants/colors';

import { store } from '_store'
import { STORE_DOLLAR_EXCHANGE_VALUE } from '_store/action'

import MoneyRecordModalView from '_views/MoneyRecordModalView'
import MoneyRecordModalPresenter from '_presenters/MoneyRecordModalPresenter'
import MoneyRecordTable from '_components/money-record-table/MoneyRecordTable'

import ForeignMoneyRecordModalView from '_views/ForeignMoneyRecordModalView'
import ForeignMoneyRecordModalPresenter from '_presenters/ForeignMoneyRecordModalPresenter'
import ForeignMoneyRecordTable from '_components/money-record-table/ForeignMoneyRecordTable'

import DenominationModalPresenter from '_presenters/DenominationModalPresenter'
import DenominationModalView from '_views/DenominationModalView'

import CashRegisterDataPresenter from '_presenters/CashRegisterDataPresenter'
import CashRegisterDataView from '_views/CashRegisterDataView'

import SalePointModalPresenter from '_presenters/SalePointModalPresenter'
import SalePointModalView from '_views/SalePointModalView'

import {decimalInputs} from '_utilities/decimalInput';
import { roundNumber } from '_utilities/mathUtilities'


export default {
    totalInputDOMS: {
        liquidMoneyBs: document.querySelector('#total_bs_cash'),
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
        liquidMoneyDollar: document.querySelector('#total_dollar_cash_saint'),
        pagoMovilBs: document.querySelector('#total_pago_movil_bs_saint'),
        pointSaleDollar: document.querySelector('#total_point_sale_dollar_saint'),
        pointSaleBs: document.querySelector('#total_point_sale_bs_saint'),
        zelleDollar: document.querySelector('#total_zelle_saint'),
    },
    totalDiffDOMS: {
        liquidMoneyBs: document.querySelector('#total_bs_cash_diff'),
        liquidMoneyDollar: document.querySelector('#total_dollar_cash_diff'),
        pagoMovilBs: document.querySelector('#total_pago_movil_bs_diff'),
        pointSaleDollar: document.querySelector('#total_point_sale_dollar_diff'),
        pointSaleBs: document.querySelector('#total_point_sale_bs_diff'),
        zelleDollar: document.querySelector('#total_zelle_diff'),
    },
    propNameToDiffTotalMethod: {
        liquidMoneyBs: 'setTotalBsCashDiff',
        pagoMovilBs: 'setTotalPagoMovilBsDiff',
        liquidMoneyDollar: 'setTotalDollarCashDiff',
        pointSaleDollar: 'setTotalPointSaleDollarDiff',
        pointSaleBs: 'setTotalPointSaleBsDiff',
        zelleDollar: 'setTotalZelleDiff',
    },
    proxyTotalSaint: null,
    proxy: null,
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
    },
    setTotalDenominationDollar(total){
        this.proxy.denominationsDollar = total
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
    handlePointSaleDollar(event){
        let total = event.target.value ? parseFloat(event.target.value) : 0
        this.setTotalPointSaleDollar(total)
    },
    setTotalSaintDOMS(totals = null){
        if (!totals){
            Object.keys(this.proxyTotalSaint).forEach(el => {
                this.proxyTotalSaint[el] = 0;
            })
        } else {

            // Liquid Money Payment Amounts
            let totalsFromSafact = totals.totals_from_safact[0];


            this.proxyTotalSaint['liquidMoneyBs'] = parseFloat(totalsFromSafact.bolivares);
            this.setTotalBsCashDiff(this);

            this.proxyTotalSaint['liquidMoneyDollar'] = parseFloat(totalsFromSafact.dolares);
            this.setTotalDollarCashDiff(this);

            let totalsEPayments = totals.totals_e_payments;

           // E-Payment Amounts
            totalsEPayments.forEach(el => {
                if (this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]] !== undefined){
                    if (el.CodPago === '01' || el.CodPago === '02'){
                        this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]] += parseFloat(el.total)
                        let amount = roundNumber(this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]])
                        this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]] = amount;
                    } else {
                        this.proxyTotalSaint[PAYMENT_CODES[el.CodPago]] = parseFloat(el.total)
                    }
                    this[this.propNameToDiffTotalMethod[PAYMENT_CODES[el.CodPago]]].call(this)
                };
            });
        }
    },
    setTotalDollarCashDiff(){
        let diff = this.proxy.liquidMoneyDollar - this.proxyTotalSaint.liquidMoneyDollar;
        let color = this.getAmountColor(diff);
        console.log(color)
        this.totalDiffDOMS.liquidMoneyDollar.className = '';
        if (color !== ''){
            this.totalDiffDOMS.liquidMoneyDollar.classList.add(color);
        }   
        this.totalDiffDOMS.liquidMoneyDollar.innerHTML = roundNumber(diff);
    },
    setTotalBsCashDiff(){
        let diff = this.proxy.liquidMoneyBs - this.proxyTotalSaint.liquidMoneyBs;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.liquidMoneyBs.className = '';
        if (color !== ''){
            this.totalDiffDOMS.liquidMoneyBs.classList.add(color);
        }   
        this.totalDiffDOMS.liquidMoneyBs.innerHTML = roundNumber(diff);
    },
    setTotalPointSaleBsDiff(){
        let diff = this.proxy.pointSaleBs - this.proxyTotalSaint.pointSaleBs;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.pointSaleBs.className = '';
        if (color !== ''){
            this.totalDiffDOMS.pointSaleBs.classList.add(color);
        }   
        this.totalDiffDOMS.pointSaleBs.innerHTML = roundNumber(diff);
    },
    setTotalPointSaleDollarDiff(){
        let diff = this.proxy.pointSaleDollar - this.proxyTotalSaint.pointSaleDollar;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.pointSaleDollar.className = '';
        if (color !== ''){
            this.totalDiffDOMS.pointSaleDollar.classList.add(color);
        }   
        this.totalDiffDOMS.pointSaleDollar.innerHTML = roundNumber(diff);
    },
    setTotalZelleDiff(){
        let diff = this.proxy.zelleDollar - this.proxyTotalSaint.zelleDollar;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.zelleDollar.className = '';
        if (color !== ''){
            this.totalDiffDOMS.zelleDollar.classList.add(color);
        }        
        this.totalDiffDOMS.zelleDollar.innerHTML = roundNumber(diff);
    },
    setTotalPagoMovilBsDiff(){
        let diff = this.proxy.pagoMovilBs - this.proxyTotalSaint.pagoMovilBs;
        let color = this.getAmountColor(diff);
        this.totalDiffDOMS.pagoMovilBs.className = '';
        if (color !== ''){
            this.totalDiffDOMS.pagoMovilBs.classList.add(color);
        }        
        this.totalDiffDOMS.pagoMovilBs.innerHTML = roundNumber(diff);
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
        }

        let handlerTotalSaintDOMS = (self, key, value) => {
            self.totalSaintDOMS[key].innerHTML = value
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

        this.proxy = new Proxy(totalInputkeys, handlerWrapper(handlerInputDOMS))
        this.proxyTotalSaint = new Proxy(totalSaintkeys, handlerWrapper(handlerTotalSaintDOMS))        
    },
    initEventListeners(){
        // // Cash register modal total input DOMs
        decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.liquidMoneyBs);
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

        // Point Sale Dollar Event Handler
        this.totalInputDOMS.pointSaleDollar.addEventListener('change', this.setPropWrapper(this.handlePointSaleDollar)) 

        store.subscribe(() => {
            let state = store.getState();
  
            if (state.lastAction === STORE_DOLLAR_EXCHANGE_VALUE ){
              document.querySelector('p[data-dollar-exchange="dollar_exchange_date"]').innerText = state.dollarExchange.createdAt
              document.querySelector('p[data-dollar-exchange="dollar_exchange_value"]').innerText = `${state.dollarExchange.value} ${CURRENCY_SIGN_MAP[CURRENCIES.BOLIVAR]}`
            }
        });
    },
    init(){

        this.initData();
        this.initEventListeners();

        let cashRegisterContainer = document.querySelector('#cash_register_data')
        let cashRegisterDataPresenter = new CashRegisterDataPresenter(this.setPropWrapper(this.setTotalSaintDOMS));
        let cashRegisterDataView = new CashRegisterDataView(cashRegisterDataPresenter);
        cashRegisterDataView.init(cashRegisterContainer)

        let liquidMoneyBsRegisterModal = document.querySelector('#bs_cash_record');
        let bolivarRecordMoneyPresenter = new MoneyRecordModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyBs));
        let bolivarRecordMoneyView = new MoneyRecordModalView(bolivarRecordMoneyPresenter);
        let moneyRecordTable = new MoneyRecordTable()
        bolivarRecordMoneyView.init(liquidMoneyBsRegisterModal, 'bs_cash_record', moneyRecordTable)

        let cashDollarRecordModal = document.querySelector('#dollar_cash_record');
        let dollarRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyDollar));
        let dollarRecordMoneyView = new ForeignMoneyRecordModalView(dollarRecordMoneyPresenter);
        let dollarRecordTable = new ForeignMoneyRecordTable()
        dollarRecordMoneyView.init(cashDollarRecordModal, 'dollar_cash_record', dollarRecordTable)

        let pagoMovilBsModal = document.querySelector('#pago_movil_record');
        let pagoMovilBsPresenter = new MoneyRecordModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalPagoMovilBs));
        let pagoMovilBsView = new MoneyRecordModalView(pagoMovilBsPresenter);
        let pagoMovilBsRecordTable = new MoneyRecordTable()
        pagoMovilBsView.init(pagoMovilBsModal, 'pago_movil_record', pagoMovilBsRecordTable)

        let bsDenominationsModal = document.querySelector('#bs_denominations_record');
        let bolivarDenominationModalPresenter = new DenominationModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationBs))
        let bolivarDenominationModalView = new DenominationModalView(bolivarDenominationModalPresenter);
        bolivarDenominationModalView.init(bsDenominationsModal, 'bs_denominations_record');

        let dollarDenominationsModal = document.querySelector('#dollar_denominations_record');
        let dollarDenominationModalPresenter = new DenominationModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationDollar))
        let dollarDenominationModalView = new DenominationModalView(dollarDenominationModalPresenter);
        dollarDenominationModalView.init(dollarDenominationsModal, 'dollar_denominations_record');

        let salePointModal = document.querySelector('#point_sale_bs');
        let salePointModalPresenter = new SalePointModalPresenter(CURRENCIES.BOLIVAR, this.setPropWrapper(this.setTotalPointSaleBs))
        let salePointModalView = new SalePointModalView(salePointModalPresenter);
        salePointModalView.init(salePointModal, 'point_sale_bs');

        let zelleRecordModal = document.querySelector('#zelle_record');
        let zelleRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.ZELLE, this.setPropWrapper(this.setTotalZelleDollar));
        let zelleRecordMoneyView = new ForeignMoneyRecordModalView(zelleRecordMoneyPresenter);
        let zelleRecordTable = new ForeignMoneyRecordTable()
        zelleRecordMoneyView.init(zelleRecordModal, 'zelle_record', zelleRecordTable)
    }
}
