import LiquidMoneyModalFactory from '_components/cash-register-modal';

export default function(){
    document.addEventListener('DOMContentLoaded', () => {
        // Containers
        let liquidMoneyBsRegisterModal = document.querySelector('#liquid_money_bolivares');
        let liquidMoneyDollarRegisterModal = document.querySelector('#liquid_money_dollars');
    
        // Cash register modal factory
        let liquidMoneyModalFactory = new LiquidMoneyModalFactory();
    
        let liquidMoneyBsRegister = liquidMoneyModalFactory.create({currency: 'bs'});
        liquidMoneyBsRegister.init(liquidMoneyBsRegisterModal);
    
        let liquidMoneyDollarRegister = liquidMoneyModalFactory.create({currency: 'dollar'});
        liquidMoneyDollarRegister.init(liquidMoneyDollarRegisterModal);
    });
}