import Inputmask from "inputmask";

export default function(){
    let inputs = document.querySelectorAll('[data-currency^="amount"]');
    let maskedInputs = [];

    const form = document.querySelector('#form');

    
    const submit = (event) => {
        let allIsNull = true;
        
        
        maskedInputs.forEach(el => {
            console.log(el.unmaskedvalue());
            if (el.unmaskedvalue()){
                allIsNull = false;
            }
        })

        if (allIsNull){
            event.preventDefault();
            alert('Epa, no se ha ingresado ningun ingreso')
            return;
        }
    }

    form.addEventListener('submit', submit);

    let moneyFormat = new Inputmask("(.999){+|1},00", {
        positionCaretOnClick: "radixFocus",
        radixPoint: ",",
        _radixDance: true,
        numericInput: true,
        placeholder: "0",
        removeMaskOnSubmit: true,
        definitions: {
            "0": {
                validator: "[0-9\uFF11-\uFF19]"
            }
        }
   })

   inputs.forEach((el) => maskedInputs.push(moneyFormat.mask(el)))
    
}