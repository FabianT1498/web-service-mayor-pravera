import toastr  from 'toastr';

import { CURRENCIES} from '_constants/currencies';
import { PAYMENT_METHODS } from '_constants/paymentMethods';

import CashRegisterData from '_components/cash-register-data'

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

export default function(){

    let bolivarRecordMoneyPresenter = new MoneyRecordModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH);
    let bolivarRecordMoneyView = new MoneyRecordModalView(bolivarRecordMoneyPresenter);
    let liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
    let moneyRecordTable = new MoneyRecordTable()
    bolivarRecordMoneyView.init(liquidMoneyBsRegisterModal, 'liquid_money_bolivares', moneyRecordTable)

    let dollarRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH);
    let dollarRecordMoneyView = new ForeignMoneyRecordModalView(dollarRecordMoneyPresenter);
    let cashDollarRecordModal = document.querySelector('#liquid_money_dollars');
    let dollarRecordTable = new ForeignMoneyRecordTable()
    dollarRecordMoneyView.init(cashDollarRecordModal, 'liquid_money_dollars', dollarRecordTable)

    let bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
    let bolivarDenominationModalPresenter = new DenominationModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH)
    let bolivarDenominationModalView = new DenominationModalView(bolivarDenominationModalPresenter);
    bolivarDenominationModalView.init(bsDenominationsModal, 'liquid_money_bolivares_denominations');

    let dollarDenominationsModal = document.querySelector('#liquid_money_dollars_denominations');
    let dollarDenominationModalPresenter = new DenominationModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH)
    let dollarDenominationModalView = new DenominationModalView(dollarDenominationModalPresenter);
    dollarDenominationModalView.init(dollarDenominationsModal, 'liquid_money_dollars_denominations');

    let salePointModal = document.querySelector('#point_sale_bs');
    let salePointModalPresenter = new SalePointModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH)
    let salePointModalView = new SalePointModalView(salePointModalPresenter);
    salePointModalView.init(salePointModal, 'point_sale_bs');

    let zelleRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.ZELLE);
    let zelleRecordMoneyView = new ForeignMoneyRecordModalView(zelleRecordMoneyPresenter);
    let zelleRecordModal = document.querySelector('#zelle_record');
    let zelleRecordTable = new ForeignMoneyRecordTable()
    zelleRecordMoneyView.init(zelleRecordModal, 'zelle_record', zelleRecordTable)

    // // Cash register modal total input DOMs
    let totalLiquidMoneyBolivares = document.querySelector('#total_liquid_money_bolivares');
    decimalInputs[CURRENCIES.BOLIVAR].mask(totalLiquidMoneyBolivares);

    let totalLiquidMoneyDollars = document.querySelector('#total_liquid_money_dollars');
    decimalInputs[CURRENCIES.DOLLAR].mask(totalLiquidMoneyDollars);

    // // Denomination modal total input DOMs
    let totalbsDenominations = document.querySelector('#total_liquid_money_bolivares_denominations');
    decimalInputs[CURRENCIES.BOLIVAR].mask(totalbsDenominations);
    
    let totaldollarDenominations = document.querySelector('#total_liquid_money_dollars_denominations');
    decimalInputs[CURRENCIES.DOLLAR].mask(totaldollarDenominations);

    // // Zelle total input DOMs
    let totalZelleEl = document.querySelector('#total_zelle_record');
    decimalInputs[CURRENCIES.DOLLAR].mask(totalZelleEl);
}