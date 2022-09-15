import { getBillPayableSchedule, getBillPayableGroupByID, linkBillPayableGroupToSchedule } from '_services/bill-payable';

const BillPayableGroupSchedulePresenterPrototype = {
	data: {
		groupID: '',
	},
	async changeOnModal({ target }) {
		const selectValue = target.value;
        
		try {
			this.data.scheduleID = selectValue;
			
			let res = await getBillPayableSchedule(selectValue);
			this.view.showScheduleData(res.data);


			res = await linkBillPayableGroupToSchedule(this.data)
		} catch(e){
			console.error(e)
		}
    },
	setBillPayableGroup(id){

		getBillPayableGroupByID(id).then(res => {
			
			
			let data = res.data.data;
			this.data.groupID = data.group.ID
			this.view.showBillPayableGroupData(data)
			this.view.showScheduleData({StartDate: data.group.ScheduleStartDate, EndDate: data.group.ScheduleEndDate});
			this.view.setSelectedSchedule(data.group.ScheduleID)

		}).catch(e => {
			console.log(e)
		});

	},
	setView(view){
		this.view = view;
	},
}

const BillPayableGroupSchedulePresenter = function (){
    this.view = null;
}

BillPayableGroupSchedulePresenter.prototype = BillPayableGroupSchedulePresenterPrototype;
BillPayableGroupSchedulePresenter.prototype.constructor = BillPayableGroupSchedulePresenter;

export default BillPayableGroupSchedulePresenter;
