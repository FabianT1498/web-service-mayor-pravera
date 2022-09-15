const BillPayableScheduleViewPrototype = {
    init(container){
        if (!container){
            return false;
        }

        this.payableBillInfoContainer = container.querySelector('#billPayableContainer')
        this.scheduleInfoContainer = container.querySelector('#scheduleContainer')

        this.closeBtnModal = container.querySelector('button[data-modal-toggle]')
        
        
        this.scheduleInfoContainer.addEventListener("change", this.changeEventHandlerWrapper(this.presenter));

        this.closeBtnModal.addEventListener('click', this.closeModalHandlerWrapper())
    },
    closeModalHandlerWrapper(){
        return (event) => {
            this.scheduleInfoContainer.querySelector('#scheduleSelect').value = "";
            this.scheduleInfoContainer.querySelector('#startDateSchedule').innerHTML = "";
            this.scheduleInfoContainer.querySelector('#endDateSchedule').innerHTML = "";
            this.cleanScheduleData();
        }
    },
    changeEventHandlerWrapper(presenter){
        return (event) => {
            presenter.changeOnModal({
                target: event.target,
            })
        }
    },
    showScheduleData(data){
        this.scheduleInfoContainer.querySelector('#startDateSchedule').innerHTML = data.StartDate ? data.StartDate : "";
        this.scheduleInfoContainer.querySelector('#endDateSchedule').innerHTML = data.EndDate ? data.EndDate : "";
    },
    cleanScheduleData(){
        this.scheduleInfoContainer.querySelector('#startDateSchedule').innerHTML = "";
        this.scheduleInfoContainer.querySelector('#endDateSchedule').innerHTML = "";
    },
    showBillPayableGroupData(data){
        this.payableBillInfoContainer.querySelector('#numeroDInfoModal').innerHTML = data.group.ID
        this.payableBillInfoContainer.querySelector('#proveedorInfoModal').innerHTML = data.group.DescripProv ?  data.group.DescripProv :  data.group.CodProv 
    },
    setSelectedSchedule(scheduleID){
        this.scheduleInfoContainer.querySelector('#scheduleSelect').value = scheduleID ? scheduleID : "";
    }
}

const BillPayableScheduleView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

BillPayableScheduleView.prototype = BillPayableScheduleViewPrototype;
BillPayableScheduleView.prototype.constructor = BillPayableScheduleView;

export default BillPayableScheduleView;