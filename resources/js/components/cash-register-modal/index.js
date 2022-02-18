import { RecordMoneyModalDollars, RecordMoneyModalBolivares } from "./types";

import { CURRENCIES } from '_assets/currencies';

const CURRENCY_CONSTRUCTORS = {[CURRENCIES.BOLIVAR]: RecordMoneyModalBolivares, [CURRENCIES.DOLLAR] : RecordMoneyModalDollars};

const RecordMoneyModalFactory = function(){
    
    // Our Factory method for creating new Modal instances
    this.create = function(options) {
        this.modalClass = CURRENCY_CONSTRUCTORS[options.currency] || RecordMoneyModalBolivares;
        return new this.modalClass(options);
    }
}

export default RecordMoneyModalFactory;