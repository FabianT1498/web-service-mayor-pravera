import PubSub from "pubsub-js";

import LiquidMoneyModalFactory from '_components/cash-register-modal';
import DenominationsModal from '_components/denominations-modal';
import SalePointModal from '_components/sale-point-modal';
import DecimalInput from '_components/decimal-input';
import CashRegisterData from '_components/cash-register-data'

export default function(){
     
    // Decimal Input Subscribers
    let decimalInputDollar = new DecimalInput();
    decimalInputDollar.init();
    
    // Containers
    let liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
    let liquidMoneyDollarRegisterModal = document.querySelector('#liquid_money_dollars');

    // Cash register modal factory
    let liquidMoneyModalFactory = new LiquidMoneyModalFactory();

    let liquidMoneyBsRegister = liquidMoneyModalFactory.create({currency: 'bs'});
    liquidMoneyBsRegister.init(liquidMoneyBsRegisterModal);

    let liquidMoneyDollarRegister = liquidMoneyModalFactory.create({currency: 'dollar'});
    liquidMoneyDollarRegister.init(liquidMoneyDollarRegisterModal);

    // Total inputs
    let totalLiquidMoneyBolivares = document.querySelector('#total_liquid_money_bolivares');
    let totalLiquidMoneyDollars = document.querySelector('#total_liquid_money_dollars');

    PubSub.publish('attachMask', {input: totalLiquidMoneyBolivares, currency: 'bs'})
    PubSub.publish('attachMask', {input: totalLiquidMoneyDollars, currency: 'dollar'})

    // Denominations modals Containers
    let bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
    let dollarDenominationsModal = document.querySelector('#liquid_money_dollars_denominations');

    let bsDenominations = new DenominationsModal('liquid_money_bolivares_denominations', 'bs');
    bsDenominations.init(bsDenominationsModal);

    let dollarDenominations = new DenominationsModal('liquid_money_dollars_denominations', 'dollar');
    dollarDenominations.init(dollarDenominationsModal);

    // Total inputs
    let totalbsDenominations = document.querySelector('#total_liquid_money_bolivares_denominations');
    let totaldollarDenominations = document.querySelector('#total_liquid_money_dollars_denominations');

    PubSub.publish('attachMask', {input: totalbsDenominations, currency: 'bs'})
    PubSub.publish('attachMask', {input: totaldollarDenominations, currency: 'dollar'})

    // Sale points
    let bsSalePointModal = document.querySelector('#point_sale_bs');
    let bsSalePoint = new SalePointModal('point_sale_bs', 'bs');
    bsSalePoint.init(bsSalePointModal);

    // Cash register data
    const cashRegisterDataContainer = document.querySelector('#cash_register_data');
    const cashRegisterData = new CashRegisterData();
    cashRegisterData.init(cashRegisterDataContainer);
}