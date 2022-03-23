import Datepicker from '@themesberg/tailwind-datepicker/Datepicker';
import es from '@themesberg/tailwind-datepicker/locales/es';

const CashRegisterDataViewPrototype = {
    init(container){
        this.container = container;
        let date = this.container.querySelector('#date');
        Object.assign(Datepicker.locales, es);
        new Datepicker(date, {
            format: 'dd-mm-yyyy',
            language: 'es'
        })
        this.container.addEventListener("change", this.changeEventHandlerWrapper(this.presenter));
        date.addEventListener('changeDate', this.changeDateEventHandlerWrapper(this.presenter))
    },
    changeEventHandlerWrapper(presenter){
        return (event) => {
            presenter.changeOnView({
                target: event.target
            })
        }
    },
    changeDateEventHandlerWrapper(presenter){
        return (event) => {
            presenter.changeDateOnView({
                date: event.detail.date
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
    },
    showCashRegisterUsersNoAvailable(){
        this.container.querySelector('#cash_register_users_message').classList.remove('hidden')
    },
    hideCashRegisterUsersNoAvailable(){
        this.container.querySelector('#cash_register_users_message').classList.add('hidden')
    },
    showLoading(){
        let el = this.container.querySelector('#cash_register_users_status').children.item(0);
       
        if(el.classList.contains('loading')){
            el.classList.remove('hidden');
        }
    },
    hideLoading(){
        let el = this.container.querySelector('#cash_register_users_status').children.item(0);
        el.classList.add('hidden');
    },
    setCashRegisterUsersElements(elements = []){
        let cashRegisterUsersSelect = this.container.querySelector('#cash_register_id')
        if (elements.length === 0){
            cashRegisterUsersSelect.disabled = true;
            cashRegisterUsersSelect.innerHTML = `<option hidden disabled value selected>No hay elementos</option>`; 
        } else {
            cashRegisterUsersSelect.disabled = false;
            cashRegisterUsersSelect.innerHTML = elements.map(el => `<option value="${el.key}"> ${el.value}</option>`).join('');
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