import { CURRENCIES, SIGN as CURRENCY_SIGN_MAP} from '_constants/currencies';
import { PAYMENT_METHODS } from '_constants/paymentMethods';

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

import CashRegisterDataPresenter from '_presenters/CashRegisterDataPresenter'
import CashRegisterDataView from '_views/CashRegisterDataView'

import {decimalInputs} from '_utilities/decimalInput';
import numericInput from '_utilities/numericInput';

import MoneyRecord from '_models/moneyRecord'
import DenominationRecord from '_models/DenominationRecord'
import PointSaleRecord from '_models/PointSaleRecord'
import Bank from '_models/Bank';

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
        let cashRegisterDataPresenter = new CashRegisterDataPresenter();
        let cashRegisterDataView = new CashRegisterDataView(cashRegisterDataPresenter);
        cashRegisterDataView.init(cashRegisterContainer)

        // Cash records bs
        let liquidMoneyBsRegisterModal = document.querySelector('#bs_cash_record');
        let cashBsRecordsElements = liquidMoneyBsRegisterModal.querySelector('tbody').children; 
        let cashBsRecords = Array.prototype.map.call(cashBsRecordsElements, function(el, key){
            let input = el.querySelector('input[id^="bs_cash_record_"]');
            decimalInputs[CURRENCIES.BOLIVAR].mask(input);
            let amount = parseFloat(input.value);
            return new MoneyRecord(amount,  CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH, key);
        });
        let bolivarRecordMoneyPresenter = new MoneyRecordModalPresenter(
            CURRENCIES.BOLIVAR,
            PAYMENT_METHODS.CASH,
            this.setPropWrapper(this.setTotalLiquidMoneyBs),
            cashBsRecords
        );
        let bolivarRecordMoneyView = new MoneyRecordModalView(bolivarRecordMoneyPresenter);
        let moneyRecordTable = new MoneyRecordTable()
        bolivarRecordMoneyView.init(liquidMoneyBsRegisterModal, 'bs_cash_record', moneyRecordTable)
        
        // Cash records dollar
        let cashDollarRecordModal = document.querySelector('#dollar_cash_record');
        let cashDollarRecordsElements = cashDollarRecordModal.querySelector('tbody').children;
        let cashDollarRecords = Array.prototype.map.call(cashDollarRecordsElements, function(el, key){
            let input = el.querySelector('input[id^="dollar_cash_record_"]');
            decimalInputs[CURRENCIES.DOLLAR].mask(input);
            let amount = parseFloat(input.value);
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
        let pointSaleBsRecords = {'credit' : [], 'debit': [], 'bank': [], 'availableBanks': []}
      
        if (pointSaleBsRecordsElements.length > 0){
            // Get the availables banks
            let bankSelectEl = salePointModal.querySelector('tbody tr select[name^="point_sale_bs_bank"]');
            if (bankSelectEl.options.length > 1){
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
                        decimalInputs[CURRENCIES.BOLIVAR].mask(creditInput);
                        decimalInputs[CURRENCIES.BOLIVAR].mask(debitInput);
                        let credit = parseFloat(creditInput.value);
                        let debit = parseFloat(debitInput.value);
                        obj['credit'].push(new PointSaleRecord(CURRENCIES.BOLIVAR, credit, bankObj, index));
                        obj['debit'].push(new PointSaleRecord(CURRENCIES.BOLIVAR, debit, bankObj, index));
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
            decimalInputs[CURRENCIES.DOLLAR].mask(input);
            let amount = parseFloat(input.value);
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