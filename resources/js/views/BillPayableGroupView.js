import { SIGN as CURRENCY_SYMBOLS_MAP} from '_constants/currencies';

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

        this.billInfoContainer = document.querySelector(`#${id}BillInfoContainer`);
 
        this.billPayableGroupModalSelect = document.querySelector(`#${id}Select`)
        this.billPayableGroupModalAddGroupBtn = document.querySelector(`#${id}AddGroupBtn`)
        
        this.billPayableGroupModalCloseBtn.addEventListener('click', this.handleClickCloseBillPayableGroup())

        this.billPayableGroupModalAddGroupBtn.addEventListener('click', this.handleClickAddGroup())

        this.billPayableGroupModalSelect.addEventListener('change', this.changeEventHandlerWrapper())
    },
    handleClickCloseBillPayableGroup: function(){
        return (event) => {
            this.billInfoContainer.querySelector('span[data-group="totalAmount"]').innerHTML = ''
            this.billInfoContainer.querySelector('span[data-group="paidAmount"]').innerHTML = ''
            this.billPayableGroupModal.hide();
        }
    },
    handleClickAddGroup: function(){
        return (event) => {
            this.presenter.handleClickAddGroup();
        }
    },
    setNewGroupInSelect(groupID){
        let newOption = this.getSelectItemTemplate({key: groupID, value: `Grupo ${groupID}`}, true)
        this.billPayableGroupModalSelect.insertAdjacentHTML('beforeend', newOption);
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
    getSelectItemTemplate: function(option, isSelected = false){
        return `
            <option value="${option.key}" ${isSelected ? 'selected' : ''}>${option.value}</option>
        `
    },
    changeEventHandlerWrapper(){
        return (event) => {
            this.presenter.changeOnModal({
                target: event.target,
            })
        }
    },
    showBillGroupDetails(data){
        this.billInfoContainer.querySelector('span[data-group="totalAmount"]').innerHTML = data.totalAmount + ' ' +  CURRENCY_SYMBOLS_MAP['dollar']
        this.billInfoContainer.querySelector('span[data-group="paidAmount"]').innerHTML = data.paidAmount + ' ' +  CURRENCY_SYMBOLS_MAP['dollar']
    }
}

const  BillPayableGroupView = function (presenter = null){
    this.presenter = presenter;
    this.presenter.setView(this);
}

BillPayableGroupView.prototype = BillPayableGroupViewPrototype;
BillPayableGroupView.prototype.constructor = BillPayableGroupView;

export default BillPayableGroupView;