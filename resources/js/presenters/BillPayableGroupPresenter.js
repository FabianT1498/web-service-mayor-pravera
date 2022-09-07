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

				console.log(res.data)

				let billPayableGroupOptions = res.data.map((item) => {
					return { key: item.ID, value: 'Grupo ' + item.ID }
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
			console.log(this.data.billsPayable)
			storeBillPayableGroup({bills: this.data.billsPayable})
				.then(res => {
					if (res.status === 200){
						let data = res.data.data;
						this.view.setNewGroupInSelect(data.groupID);
					} else {
						console.log('ha ocurrido un error')
					}
				})
				.catch(err => {

				})
		} else {

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
