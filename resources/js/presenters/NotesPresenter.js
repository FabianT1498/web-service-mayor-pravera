import NoteCollection from '_collections/noteCollection'
import Note from '_models/Note'

const NotesPresenterPrototype = {
	clickOnModal({ target, currentNoteID, data }) {
		const button = target.closest('button');

        const li = target.closest('li');

		if(button && button.tagName === 'BUTTON'){
			const action = button.getAttribute('data-modal');
            const modalToggleID = button.getAttribute('data-modal-toggle');

			if (action){
				if (action === 'add'){
                    
					if (data.description === ''){ // Check If there's a note with blank body
						return;
					}

                    if (currentNoteID === ""){
                        let note = new Note(data.title, data.description);
                        note = this.noteCollection.pushElement(note)
                        this.view.addNotePreview(note);
                        this.view.hideSavedNote(note);
                        this.view.addNewEmptyNote();
                    } else {
                        this.updateNote(currentNoteID, data)
                        this.view.showEmptyNote(currentNoteID);
                        this.view.setPreviousItemUnfocused();
                        this.view.updateListItemContent(currentNoteID, data)
                    }

				} else if(action === 'add-blank'){
                    this.view.setPreviousItemUnfocused();
                    this.view.showEmptyNote(currentNoteID);  
                } else if(action === 'delete') { // Remove element

					const noteID = button.closest('li').getAttribute('data-id');
					let id = parseInt(noteID);
					this.noteCollection.removeElementByID(id);
					this.view.deleteNotePreview(noteID)
                    this.view.showEmptyNote(noteID); 
                    this.view.deleteNote(noteID)
                    
				}
			} else if (modalToggleID){
                console.log(this.noteCollection.getAll())
                this.setTotalNotesCount(this.noteCollection.getLength());
            }
		} else if (li && li.tagName === 'LI'){
            let id = li.getAttribute('data-id');

            
            if (li.getAttribute('aria-current') === "false"){
                this.view.setPreviousItemUnfocused();
                this.view.showSelectedListItem(id);
                this.view.setListItemFocused(li)
            } else {
                this.view.setPreviousItemUnfocused();
                this.view.showEmptyNote(id);                
            }
        }
    },
    updateNote(id, data){
		let index = this.noteCollection.getIndexByID(parseInt(id));
		this.noteCollection.setElementAtIndex(index, { 
            title: data.title,
            description: data.description
        })
	},
	setView(view){
		this.view = view;
	},
}

const NotesPresenter = function (setTotalNotesCount, noteCollection = []){
    this.view = null;
    this.setTotalNotesCount = setTotalNotesCount
	this.noteCollection = new NoteCollection(noteCollection);
}

NotesPresenter.prototype = NotesPresenterPrototype;
NotesPresenter.prototype.constructor = NotesPresenter;

export default NotesPresenter;
