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

			if (this.formFilter){
				document.querySelector('#billAction').value = "SCHEDULED";

				setTimeout(() => {
					this.formFilter.submit()
				}, 1000)
			}

		} catch(e){
			document.querySelector('#billAction').value = "FAILED_SCHEDULING";
			this.formFilter.submit()
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
	setFormFilter(form){
		this.formFilter = form;
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
