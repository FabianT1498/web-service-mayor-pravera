const CashRegisterDataPrototype = {
    init(container){
        container.addEventListener("submit", this.submitEventHandler);
    },
    submitEventHandler(event){
        let allIsNull = true;
      
        for(let i = 0; i < inputs.length; i++){
            let el = inputs[i];
            
            if (el.value){
                allIsNull = false;
                break;
            }
        }
       
        // Check if there's at least one input filled
        if (allIsNull){
            event.preventDefault();
            alert('Epa, no se ha ingresado ningun ingreso')
            return;
        }
    }
}

const CashRegisterData = function (){

}

CashRegisterData.prototype = CashRegisterDataPrototype;
CashRegisterData.prototype.constructor = CashRegisterData;

export default CashRegisterData;