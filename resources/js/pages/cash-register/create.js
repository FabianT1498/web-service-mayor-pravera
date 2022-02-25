import { CURRENCIES, SIGN as CURRENCY_SIGN_MAP} from '_constants/currencies';
import { PAYMENT_METHODS } from '_constants/paymentMethods';

import CashRegisterData from '_components/cash-register-data'

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

import SalePointModalPresenter from '_presenters/SalePointModalPresenter'
import SalePointModalView from '_views/SalePointModalView'

import {decimalInputs} from '_utilities/decimalInput';

export default {
    totalInputDOMS: {
        liquidMoneyBs: document.querySelector('#total_bs_cash'),
        liquidMoneyDollar: document.querySelector('#total_dollar_cash'),
        denominationsBs: document.querySelector('#total_bs_denominations'),
        denominationsDollar: document.querySelector('#total_dollar_denominations'),
        zelleDollar: document.querySelector('#total_zelle'),
        pointSaleDollar: document.querySelector('#total_point_sale_dollar'),
        pointSaleBs: document.querySelector('#total_point_sale_bs')
    },
    proxy: null,
    setTotalLiquidMoneyBs(total){
        this.proxy.liquidMoneyBs = total
    },
    setTotalLiquidMoneyDollar(total){
        this.proxy.liquidMoneyDollar = total
    },
    setTotalDenominationBs(total){
        this.proxy.denominationsBs = total
    },
    setTotalDenominationDollar(total){
        this.proxy.denominationsDollar = total
    },
    setTotalZelleDollar(total){
        this.proxy.zelleDollar = total
    },
    setTotalPointSaleBs(total){
        this.proxy.pointSaleBs = total
    },
    setPropWrapper(fn){
        return fn.bind(this)
    },
    init(){

        let cashRegisterContainer = document.querySelector('#cash_register_data')
        let cashRegister = new CashRegisterData()
        cashRegister.init(cashRegisterContainer)

        let liquidMoneyBsRegisterModal = document.querySelector('#bs_cash_record');
        let bolivarRecordMoneyPresenter = new MoneyRecordModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyBs));
        let bolivarRecordMoneyView = new MoneyRecordModalView(bolivarRecordMoneyPresenter);
        let moneyRecordTable = new MoneyRecordTable()
        bolivarRecordMoneyView.init(liquidMoneyBsRegisterModal, 'liquid_money_bolivares', moneyRecordTable)
        
        let cashDollarRecordModal = document.querySelector('#dollar_cash_record');
        let dollarRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalLiquidMoneyDollar));
        let dollarRecordMoneyView = new ForeignMoneyRecordModalView(dollarRecordMoneyPresenter);
        let dollarRecordTable = new ForeignMoneyRecordTable()
        dollarRecordMoneyView.init(cashDollarRecordModal, 'liquid_money_dollars', dollarRecordTable)

        let bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
        let bolivarDenominationModalPresenter = new DenominationModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationBs))
        let bolivarDenominationModalView = new DenominationModalView(bolivarDenominationModalPresenter);
        bolivarDenominationModalView.init(bsDenominationsModal, 'liquid_money_bolivares_denominations');

        let dollarDenominationsModal = document.querySelector('#liquid_money_dollars_denominations');
        let dollarDenominationModalPresenter = new DenominationModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH, this.setPropWrapper(this.setTotalDenominationDollar))
        let dollarDenominationModalView = new DenominationModalView(dollarDenominationModalPresenter);
        dollarDenominationModalView.init(dollarDenominationsModal, 'liquid_money_dollars_denominations');

        let salePointModal = document.querySelector('#point_sale_bs');
        let salePointModalPresenter = new SalePointModalPresenter(CURRENCIES.BOLIVAR, this.setPropWrapper(this.setTotalPointSaleBs))
        let salePointModalView = new SalePointModalView(salePointModalPresenter);
        salePointModalView.init(salePointModal, 'point_sale_bs');

        let zelleRecordModal = document.querySelector('#zelle_record');
        let zelleRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.ZELLE, this.setPropWrapper(this.setTotalZelleDollar));
        let zelleRecordMoneyView = new ForeignMoneyRecordModalView(zelleRecordMoneyPresenter);
        let zelleRecordTable = new ForeignMoneyRecordTable()
        zelleRecordMoneyView.init(zelleRecordModal, 'zelle_record', zelleRecordTable)

        // // Cash register modal total input DOMs
        decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.liquidMoneyBs);
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.liquidMoneyDollar);

        // // Denomination modal total input DOMs
        decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.denominationsBs);
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.denominationsDollar);

        // // Zelle total input DOMs
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.zelleDollar);

        // Point sale input DOMS
        decimalInputs[CURRENCIES.DOLLAR].mask(this.totalInputDOMS.pointSaleDollar);
        decimalInputs[CURRENCIES.BOLIVAR].mask(this.totalInputDOMS.pointSaleBs);

        let handlerWrapper = () => {
            let self = this;
            return {
                set: function(target, key, value) {
                    target[key] = value;
                    self.totalInputDOMS[key].value = value
                    return true;
                },
            }
        }

        let keys = Object.keys(this.totalInputDOMS).reduce((obj, key) => {
            obj[key] = 0;
            return obj;
        }, {})

        this.proxy = new Proxy(keys, handlerWrapper())

        store.subscribe(() => {
            let state = store.getState();
        
            if (state.lastAction === STORE_DOLLAR_EXCHANGE_VALUE ){
                document.querySelector('p[data-dollar-exchange="dollar_exchange_date"]').innerText = state.dollarExchange.createdAt
                document.querySelector('p[data-dollar-exchange="dollar_exchange_value"]').innerText = `${state.dollarExchange.value} ${CURRENCY_SIGN_MAP[CURRENCIES.BOLIVAR]}`
            }
        })
    }
}