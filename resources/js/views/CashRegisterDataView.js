import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';
import es from '@themesberg/tailwind-datepicker/locales/es';

const CashRegisterDataViewPrototype = {
    init(container){
        this.container = container;
        this.container.addEventListener("change", this.changeEventHandlerWrapper(this.presenter));

        let date = this.container.querySelector('#date');
        Object.assign(Datepicker.locales, es);
        new Datepicker(date, {
            format: 'dd-mm-yyyy'
        });
    },
    changeEventHandlerWrapper(presenter){
        return (event) => {
            presenter.changeOnView({
                target: event.target
            })
        }
    },
    toggleNewWorkerContainer(){
        let workersSelectEl = this.container.querySelector('#cash_register_worker');
        let newCashRegisterWorkerContainer = this.container.querySelector('#new_cash_register_worker_container');
        
        if (newCashRegisterWorkerContainer && workersSelectEl){
            newCashRegisterWorkerContainer.classList.toggle('hidden');
            newCashRegisterWorkerContainer.querySelector('input').toggleAttribute('required');

            workersSelectEl.disabled = !workersSelectEl.disabled;
            workersSelectEl.toggleAttribute('required');
            
            if (workersSelectEl.disabled){
                workersSelectEl.selectedIndex = "0"
            }
        }
    }
}

/**
 * event.target.value = event.target.value === "0" ? "1" : "0"
            let workersSelectEl = container.querySelector('#cash_register_worker');
            let newCashRegisterWorkerContainer = container.querySelector('#new_cash_register_worker_container');
            
            if (newCashRegisterWorkerContainer && workersSelectEl){
                newCashRegisterWorkerContainer.classList.toggle('hidden');
                newCashRegisterWorkerContainer?.lastElementChild?.toggleAttribute('required');

                workersSelectEl.disabled = !workersSelectEl.disabled;
                workersSelectEl.toggleAttribute('required');
                
                if (workersSelectEl.disabled){
                    workersSelectEl.selectedIndex = "0"
                }
            }
 */

/**
 * It represents the cash register data component
 * @constructor
 */
const CashRegisterDataView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

CashRegisterDataView.prototype = CashRegisterDataViewPrototype;
CashRegisterDataView.prototype.constructor = CashRegisterDataView;

export default CashRegisterDataView;