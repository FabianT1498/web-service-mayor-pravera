import { getBillPayableSchedule, linkBillPayableToSchedule, getBillPayable } from '_services/bill-payable';

const BillPayableSchedulePresenterPrototype = {
	data: {},
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
	setBillPayable(data){
		this.data = data;
		this.view.showBillPayableData(data)

		getBillPayable(this.data).then(res => {
			console.log(data)
			if (res.data.length > 0){
				let data = res.data[0];
				
				this.view.showScheduleData({StartDate: data.ScheduleStartDate, EndDate: data.ScheduleEndDate});
				this.view.setSelectedSchedule(data.ScheduleID)
			}
		}).catch(e => {
			console.log(e)
		});

	},
	setView(view){
		this.view = view;
	},
}

const BillPayableSchedulePresenter = function (){
    this.view = null;
}

BillPayableSchedulePresenter.prototype = BillPayableSchedulePresenterPrototype;
BillPayableSchedulePresenter.prototype.constructor = BillPayableSchedulePresenter;

export default BillPayableSchedulePresenter;
