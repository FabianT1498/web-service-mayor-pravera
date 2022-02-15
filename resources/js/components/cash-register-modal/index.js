import { LiquidMoneyModalDollars, LiquidModalBolivares } from "./types";

const CURRENCIES = {'bs': LiquidMoneyModalBolivares, 'dollar' : LiquidMoneyModalDollars};

const LiquidMoneyModalFactory = function(){
    
    // Our Factory method for creating new Modal instances
    this.create = function(options) {
        this.modalClass = CURRENCIES[options.type] || LiquidModalBolivares;
        return new this.modalClass(options);
    }
}

export default LiquidMoneyModalFactory;

