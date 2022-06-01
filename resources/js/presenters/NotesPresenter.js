import NoteCollection from '_collections/noteCollection'
import Note from '_models/Note'

const NotesPresenterPrototype = {
	clickOnModal({ target, data }) {
		const button = target.closest('button');

		if(button && button.tagName === 'BUTTON'){
			const action = button.getAttribute('data-modal');
		  	const modalToggleID = button.getAttribute('data-modal-toggle');

			if (action){
				if (action === 'add'){
					if (data.description === ''){ // Check If there's a note with blank body
						return;
					}

					let note = new Note(data.title, data.description);
					note = this.noteCollection.pushElement(note)
                    this.view.addNotePreview(note);
                    // this.view.hideSavedNote();
					// this.view.addNewEmptyNote();
				} else if(action === 'remove') { // Remove element
					// const rowID = button.getAttribute('data-del-row');
					// let id = parseInt(rowID);
					// this.moneyRecordCollection.removeElementByID(id);
					// this.view.deleteRow(rowID)
				}
			}
		  	else if (modalToggleID){ // Checking if it's closing the modal
				// const total = roundNumber(this.moneyRecordCollection.getAll().reduce((acc, curr) => acc + curr.amount, 0))
				// this.setTotalAmount(total)
		  	}
		}
   },
    keyPressedOnModal({target, key}){
   		// if (key === 13 || key === 'Enter'){ // Handle new table's row creation or jump to next input
			
		// 	const targetRow = target.closest('tr');
		// 	let simbling = targetRow.nextElementSibling;

		// 	let current = this.moneyRecordCollection.getElementByID(parseInt(targetRow.getAttribute('data-id')));

		// 	if (simbling){ // Check If there's a zero value
		// 		this.view.setFocusOnInput(simbling);
		// 	} else if (!simbling && current.amount != 0){
		// 		let moneyRecord = new MoneyRecord(0, this.currency, this.method);
		// 		moneyRecord = this.moneyRecordCollection.pushElement(moneyRecord)
		// 	 	this.view.addRow({ ...moneyRecord, total: this.moneyRecordCollection.getLength()});
		// 	}

        // } else if (isFinite(key)){
		// 	let id = target.closest('tr').getAttribute('data-id');
		// 	this.updateMoneyRecord(parseInt(id), target.value)
		// }
   	},
	setView(view){
		this.view = view;
	},
	// updateMoneyRecord(id, inputValue){
	// 	let index = this.moneyRecordCollection.getIndexByID(parseInt(id));
    // 	let value = formatAmount(inputValue);
	// 	this.moneyRecordCollection.setElementAtIndex(index, { amount: value })
	// },
}

const NotesPresenter = function (noteCollection = []){
    this.view = null;
	this.noteCollection = new NoteCollection(noteCollection);
}

NotesPresenter.prototype = NotesPresenterPrototype;
NotesPresenter.prototype.constructor = NotesPresenter;

export default NotesPresenter;
