import LiquidMoneyModalFactory from '_components/cash-register-modal';
import PubSub from "pubsub-js";

import DecimalInput from '_components/decimal-input';

export default function(){
    document.addEventListener('DOMContentLoaded', () => {
        
        // Decimal Input Subscribers
        let decimalInputDollar = new DecimalInput('dollar');
        decimalInputDollar.init();

        let decimalInputBs = new DecimalInput('bs');
        decimalInputBs.init();
        
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

        PubSub.publish('attachMask.bs', {input: totalLiquidMoneyBolivares})
        PubSub.publish('attachMask.dollar', {input: totalLiquidMoneyDollars})

    });
}