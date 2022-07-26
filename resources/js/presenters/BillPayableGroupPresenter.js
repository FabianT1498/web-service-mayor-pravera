import swal from 'sweetalert';

import { getBillPayableGroup, storeBillPayableGroup, updateBillPayableGroup } from '_services/bill-payable';

import BillPayableGroupCollection from '_collections/BillPayableGroupCollection'
import BillPayableGroup from '_models/BillPayableGroup'



const BillPayableGroupPresenterPrototype = {
	data: {
		codProv: '',
		provDescrip: '',
		billsPayable: []
	},
	async changeOnModal({ target }) {
		const selectValue = target.value;
        
		try {
			const el = this.billPayableGroups.getElementByID(parseInt(selectValue))

			updateBillPayableGroup(el.id, {bills: this.data.billsPayable})
				.then(res => {
					if (res.status === 200){
						let data = res.data.data;
						let index = this.billPayableGroups.getIndexByID(data.group.ID)

						this.billPayableGroups.setElementAtIndex(index, new BillPayableGroup(
							data.group.ID,
							data.group.Estatus,
							data.group.CodProv,
							data.group.MontoTotal,
							data.group.MontoPagado
						))

						let group = this.billPayableGroups.getElementByIndex(index)
						this.view.showBillGroupDetails(group)
						
						if (this.formFilter){
							document.querySelector('#billAction').value = "GROUPED";

							setTimeout(() => {
								this.formFilter.submit()
							}, 1000)
						}
					
					} else {
						console.log('ha ocurrido un error')

						console.log(res)

						swal({
							title: 'No se ha podido cambiar las facturas a un nuevo grupo',
							icon: "error",
							button: 'Cerrar',
							timer: 5000,
						});
					}
				})
				.catch(err => {
					console.log(err);
					swal({
						title: 'No se ha podido cambiar las facturas a un nuevo grupo',
						icon: "error",
						button: 'Cerrar',
						timer: 5000,
					});
				})

		} catch(e){
			console.error(e)
		}
    },
	setBillPayableProvider(data){
		this.data = data;
		this.view.setBillPayableProvider(data)

		getBillPayableGroup(this.data)
			.then(res => {

				let groups = res.data.map((el) => {
					return new BillPayableGroup(
						el.ID,
						el.Estatus,
						el.CodProv,
						el.MontoTotal,
						el.MontoPagado
					)
				})
				
				
				this.billPayableGroups.setElements(groups);
				
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
	setFormFilter(form){
		this.formFilter = form;
	},
	handleClickAddGroup(){
		if (this.data.billsPayable.length > 0){

			storeBillPayableGroup({bills: this.data.billsPayable, cod_prov: this.data.codProv})
				.then(res => {
					if (res.status === 200){
						let data = res.data.data;
						
						
						let newGroup = new BillPayableGroup(
							data.group.ID,
							data.group.Estatus,
							data.group.CodProv,
							data.group.MontoTotal,
							data.group.MontoPagado
						)
						
						this.billPayableGroups.pushElement(newGroup)
						this.view.showBillGroupDetails(newGroup)
						this.view.setNewGroupInSelect(newGroup.id);

						document.querySelector('#billAction').value = "GROUPED";
					
						if (this.formFilter){
							document.querySelector('#billAction').value = 1;

							setTimeout(() => {
								this.formFilter.submit()
							}, 1000)
						}

					} else {
						
						swal({
							title: 'No se ha podido crear un nuevo grupo',
							text: 'Ya existe un grupo para este proveedor sin facturas asociadas',
							icon: "error",
							button: 'Cerrar',
							timer: 5000,
						});
					}
				})
				.catch(err => {
					console.log(err)
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

	this.billPayableGroups = new BillPayableGroupCollection();
}

BillPayableGroupPresenter.prototype = BillPayableGroupPresenterPrototype;
BillPayableGroupPresenter.prototype.constructor = BillPayableGroupPresenter;

export default BillPayableGroupPresenter;
