import { CURRENCIES} from '_constants/currencies';
import { PAYMENT_METHODS } from '_constants/paymentMethods';

// import RecordMoneyModalFactory from '_components/cash-register-modal';
// import DenominationsModal from '_components/denominations-modal';
// import SalePointModal from '_components/sale-point-modal';
// import DecimalInput from '_components/decimal-input';
// import CashRegisterData from '_components/cash-register-dbata'

import MoneyRecordModalView from '_views/MoneyRecordModalView'
import MoneyRecordModalPresenter from '_presenters/MoneyRecordModalPresenter'
import MoneyRecordTable from '_components/money-record-table/MoneyRecordTable'

import ForeignMoneyRecordModalView from '_views/ForeignMoneyRecordModalView'
import ForeignMoneyRecordModalPresenter from '_presenters/ForeignMoneyRecordModalPresenter'
import ForeignMoneyRecordTable from '_components/money-record-table/ForeignMoneyRecordTable'

import DenominationModalPresenter from '_presenters/DenominationModalPresenter'
import DenominationModalView from '_views/DenominationModalView'

export default function(){

    let moneyRecordMoneyPresenter = new MoneyRecordModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH);
    let moneyRecordMoneyView = new MoneyRecordModalView(moneyRecordMoneyPresenter);
    let liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
    let moneyRecordTable = new MoneyRecordTable()
    moneyRecordMoneyView.init(liquidMoneyBsRegisterModal, 'liquid_money_bolivares', moneyRecordTable)

    let foreignMoneyRecordMoneyPresenter = new ForeignMoneyRecordModalPresenter(CURRENCIES.DOLLAR, PAYMENT_METHODS.CASH);
    let foreignMoneyRecordMoneyView = new ForeignMoneyRecordModalView(foreignMoneyRecordMoneyPresenter);
    let cashDollarRecordModal = document.querySelector('#liquid_money_dollars');
    let foreignMoneyRecordTable = new ForeignMoneyRecordTable()
    foreignMoneyRecordMoneyView.init(cashDollarRecordModal, 'liquid_money_dollars', foreignMoneyRecordTable)

    let bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
    let denominationModalPresenter = new DenominationModalPresenter(CURRENCIES.BOLIVAR, PAYMENT_METHODS.CASH)
    let denominationModalView = new DenominationModalView(denominationModalPresenter);
    denominationModalView.init(bsDenominationsModal, 'liquid_money_bolivares_denominations');

    // // Cash register data DOM
    // const cashRegisterDataContainer = document.querySelector('#cash_register_data');

    // const cashRegisterData = new CashRegisterData();
    // cashRegisterData.init(cashRegisterDataContainer);
     
    // // Decimal Input Subscribers
    // let decimalInputDollar = new DecimalInput();
    // decimalInputDollar.init();
    
    // // Cash register modal DOMs
    // let liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
    // let liquidMoneyDollarRegisterModal = document.querySelector('#liquid_money_dollars');

    // // Cash register modal factory
    // let recordMoneyModalFactory = new RecordMoneyModalFactory();

    // let liquidMoneyBsRegister = recordMoneyModalFactory.create({currency: CURRENCIES.BOLIVAR, method: 'cash'});
    // liquidMoneyBsRegister.init(liquidMoneyBsRegisterModal, 'liquid_money_bolivares');

    // let liquidMoneyDollarRegister = recordMoneyModalFactory.create({currency: CURRENCIES.DOLLAR, method: 'cash'});
    // liquidMoneyDollarRegister.init(liquidMoneyDollarRegisterModal, 'liquid_money_dollars');

    // // Cash register modal total input DOMs
    // let totalLiquidMoneyBolivares = document.querySelector('#total_liquid_money_bolivares');
    // let totalLiquidMoneyDollars = document.querySelector('#total_liquid_money_dollars');

    // PubSub.publish('attachMask', {input: totalLiquidMoneyBolivares, currency: CURRENCIES.BOLIVAR})
    // PubSub.publish('attachMask', {input: totalLiquidMoneyDollars, currency: CURRENCIES.DOLLAR})

    // // Denomination modal DOMs
    // let bsDenominationsModal = document.querySelector('#liquid_money_bolivares_denominations');
    // let dollarDenominationsModal = document.querySelector('#liquid_money_dollars_denominations');

    // let bsDenominations = new DenominationsModal('liquid_money_bolivares_denominations', CURRENCIES.BOLIVAR);
    // bsDenominations.init(bsDenominationsModal);

    // let dollarDenominations = new DenominationsModal('liquid_money_dollars_denominations', CURRENCIES.DOLLAR);
    // dollarDenominations.init(dollarDenominationsModal);

    // // Denomination modal total input DOMs
    // let totalbsDenominations = document.querySelector('#total_liquid_money_bolivares_denominations');
    // let totaldollarDenominations = document.querySelector('#total_liquid_money_dollars_denominations');

    // PubSub.publish('attachMask', {input: totalbsDenominations, currency: CURRENCIES.BOLIVAR})
    // PubSub.publish('attachMask', {input: totaldollarDenominations, currency: CURRENCIES.DOLLAR})

    // // Sale point DOM
    // let bsSalePointModal = document.querySelector('#point_sale_bs');
    // let bsSalePoint = new SalePointModal('point_sale_bs', CURRENCIES.BOLIVAR);
    // bsSalePoint.init(bsSalePointModal);

    // // Zelle list DOM
    // let zelleRecordModal = document.querySelector('#zelle_record')
    // let zelleRecord = recordMoneyModalFactory.create({currency: CURRENCIES.DOLLAR, method: 'zelle'});
    // zelleRecord.init(zelleRecordModal, 'zelle_record');

    // // Zelle total input DOMs
    // let totalZelleEl = document.querySelector('#total_zelle_record');
    // PubSub.publish('attachMask', {input: totalZelleEl, currency: CURRENCIES.DOLLAR})

    // document.querySelector('#form').addEventListener('submit', (event) =>{
    //     let allIsNull = true;
      
    //     for(let i = 0; i < inputs.length; i++){
    //         let el = inputs[i];
            
    //         if (el.value){
    //             allIsNull = false;
    //             break;
    //         }
    //     }
       
    //     // Check if there's at least one input filled
    //     if (allIsNull){
    //         event.preventDefault();
    //         alert('Epa, no se ha ingresado ningun ingreso')
    //         return;
    //     }
    // })
}