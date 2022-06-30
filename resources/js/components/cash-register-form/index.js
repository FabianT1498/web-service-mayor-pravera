const CashRegisterDataPrototype = {
    init(container){
        container.addEventListener("submit", this.submitEventHandler);
    },
    
}

const CashRegisterData = function (){

}

CashRegisterData.prototype = CashRegisterDataPrototype;
CashRegisterData.prototype.constructor = CashRegisterData;

export default CashRegisterData;