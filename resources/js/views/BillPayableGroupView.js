const BillPayableGroupViewPrototype = {
    init(container){
        if (!container){
            return false;
        }

        let id = container.getAttribute('id');

        this.billPayableGroupModal = new Modal(container);

        this.billPayableGroupModalCloseBtn = document.querySelector(`#${id}CloseBtn`)

        this.billPayableGroupSelect = document.querySelector(`#${id}Select`)

        this.infoProvider = document.querySelector(`#${id}InfoProvider`);
 
        this.billPayableGroupModalSelect = document.querySelector(`#${id}Select`)
        this.billPayableGroupModalAddGroupBtn = document.querySelector(`#${id}AddGroupBtn`)
        
        this.billPayableGroupModalCloseBtn.addEventListener('click', this.handleClickCloseBillPayableGroup())

        this.billPayableGroupModalAddGroupBtn.addEventListener('click', this.handleClickAddGroup())
    },
    handleClickCloseBillPayableGroup: function(){
        return (event) => {
            this.billPayableGroupModal.hide();
        }
    },
    handleClickAddGroup: function(){
        return (event) => {
            this.presenter.handleClickAddGroup();
        }
    },
    showModal: function(){
        this.billPayableGroupModal.show();
    },
    setBillPayableProvider: function(data){
        this.infoProvider.innerHTML = data.provDescrip;
    },
    setAvailableGroups: function(options){
        let optionsHTML = options.map(option => this.getSelectItemTemplate(option)).join('');

        let defaultOption = `<option selected="selected" value hidden>${optionsHTML === '' ? 'No existen grupos' : 'Seleccione un grupo'}</option>`

        optionsHTML = defaultOption + optionsHTML;

        this.billPayableGroupModalSelect.innerHTML = '';

        this.billPayableGroupModalSelect.insertAdjacentHTML('beforeend', optionsHTML)
    },
    getSelectItemTemplate: function(option){
        return `
            <option value="${option.key}" selected>${option.value}</option>
        `
    }
    // closeModalHandlerWrapper(){
    //     return (event) => {
    //         this.scheduleInfoContainer.querySelector('#scheduleSelect').value = "";
    //         this.scheduleInfoContainer.querySelector('#startDateSchedule').innerHTML = "";
    //         this.scheduleInfoContainer.querySelector('#endDateSchedule').innerHTML = "";
    //         this.cleanScheduleData();
    //     }
    // },
    // changeEventHandlerWrapper(presenter){
    //     return (event) => {
    //         presenter.changeOnModal({
    //             target: event.target,
    //         })
    //     }
    // },
    // showScheduleData(data){
    //     this.scheduleInfoContainer.querySelector('#startDateSchedule').innerHTML = data.StartDate ? data.StartDate : "";
    //     this.scheduleInfoContainer.querySelector('#endDateSchedule').innerHTML = data.EndDate ? data.EndDate : "";
    // },
    // cleanScheduleData(){
    //     this.scheduleInfoContainer.querySelector('#startDateSchedule').innerHTML = "";
    //     this.scheduleInfoContainer.querySelector('#endDateSchedule').innerHTML = "";
    // },
    // showBillPayableData({numeroD, provDescrip}){
    //     this.payableBillInfoContainer.querySelector('#numeroDInfoModal').innerHTML = numeroD
    //     this.payableBillInfoContainer.querySelector('#proveedorInfoModal').innerHTML = provDescrip
    // },
    // setSelectedSchedule(scheduleID){
    //     this.scheduleInfoContainer.querySelector('#scheduleSelect').value = scheduleID ? scheduleID : "";
    // }
}

const  BillPayableGroupView = function (presenter = null){
    this.presenter = presenter;
    this.presenter.setView(this);
}

BillPayableGroupView.prototype = BillPayableGroupViewPrototype;
BillPayableGroupView.prototype.constructor = BillPayableGroupView;

export default BillPayableGroupView;