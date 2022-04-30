import PointSaleCollection from '_collections/poinSaleCollection';
import BankCollection from '_collections/bankCollection';

import { getAllBanks } from '_services/banks'

import POINT_SALE_TYPE from '_constants/point-sale-type'

import { formatAmount, roundNumber } from '_utilities/mathUtilities'

import PointSaleRecord from '_models/PointSaleRecord'
import Bank from '_models/Bank'

const SalePointModalPresenterPrototype = {
	clickOnModal({ target }) {
		const button = target.closest('button');

		if(button && button.tagName === 'BUTTON'){
		   const action = button.getAttribute('data-modal')
		   const modalToggleID = button.getAttribute('data-modal-toggle');

			if (action){
				if (action === 'add'){
					if (this.pointSaleDebit.getLast() && this.pointSaleCredit.getLast() 
							&& this.pointSaleDebit.getLast().total === 0 && this.pointSaleCredit.getLast().total === 0){ // Check If there's a zero value
						return;
					}

					this.addNewRow()
				} else if (action === 'delete'){

					let row = button.closest('tr');
					let id = row ? parseInt(row.getAttribute('data-id')) : null

					let bank = this.selectedBanks.getElementByID(id);

					if ( bank === undefined){
						return false;
					}

					this.pointSaleDebit.removeElementByBankID(bank)
					this.pointSaleCredit.removeElementByBankID(bank)

					this.selectedBanks.removeElementByID(id)

					this.banks.push(bank.name);

					let idArr = this.selectedBanks.getAll().map((el) => el.id);

					this.view.deleteRow({
						prevIDArr: idArr,
						deleteID: id,
						availableBanks: this.banks,
						totalElements: this.selectedBanks.getLength()
					});
				}
			} else if(modalToggleID){
			
				const totalCredit = this.pointSaleCredit.getAll().reduce((acc, curr) => acc + curr.total, 0)
				const totalDebit = this.pointSaleDebit.getAll().reduce((acc, curr) => acc + curr.total, 0)
				this.setTotalAmount(roundNumber(totalCredit + totalDebit))
			}
		}
   	},
	changeOnModal({target}){
		if (target.tagName !== 'SELECT'){
			return;
		}

		if (this.banks.length === 0){
			return;
		}

		let row = target.closest('tr');
		let id = row && row.getAttribute('data-id') ? parseInt(row.getAttribute('data-id')) : null

		if (id !== null){
			// Old bank selected
			let bank = this.selectedBanks.getElementByID(id);

			// New Bank selected
            let index = target.selectedIndex;

            let newSelectedValue = target.options[index].value;

			// Old value is pushed again in banks array
			this.banks.push(bank.name);

			// Remove the new value from available banks
			let indexNew = this.banks.indexOf(newSelectedValue);
			this.banks.splice(indexNew, 1);

			// Set the new value in old value select
			let indexOld = this.selectedBanks.getIndexByID(id)
			this.selectedBanks.setElementAtIndex(indexOld, { name: newSelectedValue});

			// indexOld it's the same to pointSaleDebit and pointSaleCredit
			this.pointSaleCredit.setElementAtIndex(indexOld, { bank: { ...bank, name: newSelectedValue }})
			this.pointSaleDebit.setElementAtIndex(indexOld, { bank: { ...bank, name: newSelectedValue }})

			this.view.changeSelect({
				prevIDArr: this.selectedBanks.getAll().map((el) => el.id),
				availableBanks: this.banks
			})
        }
	},
	keyPressedOnModal({target, key}){
		if (isFinite(key)){
			let id = target.closest('tr').getAttribute('data-id');
			let type = target.getAttribute('data-point-sale-type')
			this.updatePointSaleRecord(parseInt(id), type, target.value)
	 	} if (key === 13 || key === 'Enter'){ // Handle new table's row creation or jump to next input
			
			const targetRow = target.closest('tr');
			let id = parseInt(targetRow.getAttribute('data-id'));
			let debit = this.pointSaleDebit.getElementByID(id)
			let credit = this.pointSaleCredit.getElementByID(id)

			let simbling = targetRow.nextElementSibling;
			
			if (simbling){
				this.view.setFocusOnInput(simbling);
			} else if(!simbling && (debit.total > 0 || credit.total > 0)) {
				this.addNewRow()
			}
        }
	},
	keyDownOnModal({target, key}){
		if (key === 8 || key === 'Backspace'){
            let id = target.closest('tr').getAttribute('data-id');
			let type = target.getAttribute('data-point-sale-type')
			this.updatePointSaleRecord(parseInt(id), type, target.value)
        }
	},
	updatePointSaleRecord(id, type, inputValue){
		let index = this.selectedBanks.getIndexByID(id)
		let value = formatAmount(inputValue);

		if (type === POINT_SALE_TYPE.DEBIT){
			this.pointSaleDebit.setElementAtIndex(index, { total: value })
		} else if (type === POINT_SALE_TYPE.CREDIT){
			this.pointSaleCredit.setElementAtIndex(index, { total: value })
		}
	},
	setView(view){
		this.view = view;
	},
	fetchInitialData: async function(){
        try {
            const banks = await getAllBanks();
            return {banks}
        } catch(e){
            return { banks: [] }
        }
   	},
	addNewRow(){
		if (this.banks.length === 0){
			return;
		}

		let idArr = [];
		let bank = new Bank(this.banks.shift());

		if (this.selectedBanks.getLength() > 0){
			idArr = this.selectedBanks.getAll().map((el) => el.id);
			bank = this.selectedBanks.pushElement(bank);
		} else {
			bank = this.selectedBanks.pushElement(bank);
		}

		// Add point sale records to collection
		this.pointSaleDebit.pushElement(new PointSaleRecord(this.currency, 0, bank))
		this.pointSaleCredit.pushElement(new PointSaleRecord(this.currency, 0, bank))

		this.view.addRow({
			prevIDArr: idArr,
			newID: bank.id,
			currentBank: bank.name,
			availableBanks: this.banks,
			totalElements: this.selectedBanks.getLength()
		});
	}
}

const SalePointModalPresenter = function (currency, setTotalAmount, pointSaleRecords = {}){
   this.view = null;
	this.currency = currency;
	this.banks = [];
	this.setTotalAmount = setTotalAmount
	this.selectedBanks = new BankCollection();
	this.pointSaleDebit = new PointSaleCollection();
	this.pointSaleCredit = new PointSaleCollection();

	if (Object.keys(pointSaleRecords).length > 0
			&& ("bank" in pointSaleRecords && pointSaleRecords['bank'].length > 0)
					&& ("credit" in pointSaleRecords) && ("debit" in pointSaleRecords)
							&& "availableBanks" in pointSaleRecords){
		this.selectedBanks.setElements(pointSaleRecords['bank']);
		this.pointSaleDebit.setElements(pointSaleRecords['debit']);
		this.pointSaleCredit.setElements(pointSaleRecords['credit']);
		this.banks = pointSaleRecords['availableBanks']
	} else {
		this.fetchInitialData()
			.then(res => {
				this.banks = res.banks;
			})
			.catch(err => {
				console.log(err)
			});
	}
}

SalePointModalPresenter.prototype = SalePointModalPresenterPrototype;
SalePointModalPresenter.prototype.constructor = SalePointModalPresenter;

export default SalePointModalPresenter;
