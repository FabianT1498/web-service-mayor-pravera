import PubSub from "pubsub-js";

const LiquidMoneyModal = function (){

    this.init = function(container){
        container.addEventListener("keypress", this.keypressEventHandler);
        container.addEventListener("click", this.clickEventHandler);
    }

    this.clickEventHandler = function(event){
        const closest = event.target.closest('button');

        if(closest && closest.tagName === 'BUTTON'){

            const idRow = closest.getAttribute('data-del-row');
            const modaToggleID = closest.getAttribute('data-modal-toggle');
            
            if (idRow){ // Checking if it's Deleting a row
                let parent = document.querySelector(`#${this.id} tbody`)

                if(parent.children.length === 1){
                    const input = document.getElementById(`${this.id}_${idRow}`);
                    if (input?.inputmask){
                        input.value = 0;
                        const convertionCol = closest?.closest('tr')?.children[2];
                        const dataConvertionCol = convertionCol ? convertionCol?.getAttribute('data-table') : null;
                        if (dataConvertionCol && dataConvertionCol === 'convertion-col'){
                            convertionCol.innerHTML = `0.00 Bs.s`
                        }
                        // input.inputmask.remove();
                        // decimalMaskOptions.suffix = this.getAttribute('data-currency');
                        // (new Inputmask(decimalMaskOptions)).mask(input);
                    }
                } else {
                    let child = document.querySelector(`#${this.id} tr[data-id="${idRow}"]`)
                    parent.removeChild(child);
                    modalsID[`${this.id}_count`]--;
                    removeInputID(this.id, idRow)
                    updateTableIDColumn(this.id);
                }

            } else if (modaToggleID){ // Checking if it's closing the modal

                // get all inputs of the modal
                let inputs = document.querySelectorAll(`#${this.id} input`)
                const total = Array.from(inputs).reduce((acc, el) => {
                    let num = formatAmount(el.value)
                    return acc + num;
                }, 0);

                document.getElementById(`total_${this.id}`).value = total > 0 ? total : 0;
            }
        }
    };
}

export default LiquidMoneyModal;