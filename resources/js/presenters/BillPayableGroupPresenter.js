import { getBillPayableGroup, storeBillPayableGroup } from '_services/bill-payable';

const BillPayableGroupPresenterPrototype = {
	data: {
		codProv: '',
		provDescrip: '',
		billPayableGroups: [],
		billsPayable: []
	},
	async changeOnModal({ target }) {
		const selectValue = target.value;
        
		try {
			this.data.scheduleID = selectValue;
			
			let res = await getBillPayableSchedule(selectValue);
			this.view.showScheduleData(res.data);

			res = await linkBillPayableToSchedule(this.data)
		} catch(e){
			console.error(e)
		}
    },
	setBillPayableProvider(data){
		this.data = data;
		this.view.setBillPayableProvider(data)

		getBillPayableGroup(this.data)
			.then(res => {
				this.data.billPayableGroups = res.data;

				let billPayableGroupOptions = res.data.map((item) => {
					return { key: item.id, value: 'Grupo ' + item.id }
				})

				this.view.setAvailableGroups(billPayableGroupOptions)
			})
			.catch(err => {
				console.log()
			})

	},
	setBillsPayable(data){
		this.data.billsPayable = data;
	},
	handleClickAddGroup(){
		if (this.data.billsPayable.length > 0){

		}
	},
	setView(view){
		this.view = view;
	},
}

const BillPayableGroupPresenter = function (){
    this.view = null;
}

BillPayableGroupPresenter.prototype = BillPayableGroupPresenterPrototype;
BillPayableGroupPresenter.prototype.constructor = BillPayableGroupPresenter;

export default BillPayableGroupPresenter;
