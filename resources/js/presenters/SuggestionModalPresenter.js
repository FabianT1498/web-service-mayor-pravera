import SuggestionCollection from '_collections/suggestionCollection'
import Suggestion from '_models/Suggestion'


import { storeProductSuggestions, getProductSuggestions } from '_services/products';

const SuggestionModalPresenterPrototype = {
	clickOnModal({ target, currentNoteID, data }) {
		const button = target.closest('button');

        const li = target.closest('li');

		if(button && button.tagName === 'BUTTON'){
			const action = button.getAttribute('data-modal');
            const modalToggleID = button.getAttribute('data-modal-toggle');

			if (action){
				if (action === 'add'){
                    
					// if (data.description === ''){ // Check If there's a note with blank body
					// 	return;
					// }

                    // if (currentNoteID === ""){
                    //     let note = new Note(data.title, data.description);
                    //     note = this.noteCollection.pushElement(note)
                    //     this.view.addNotePreview(note);
                    //     this.view.hideSavedNote(note);
                    //     this.view.addNewEmptyNote();
                    // } else {
                    //     this.updateNote(currentNoteID, data)
                    //     this.view.showEmptyNote(currentNoteID);
                    //     this.view.setPreviousItemUnfocused();
                    //     this.view.updateListItemContent(currentNoteID, data)
                    // }

				} else if(action === 'add-blank'){
                    // this.view.setPreviousItemUnfocused();
                    // this.view.showEmptyNote(currentNoteID);  
                } else if(action === 'delete') { // Remove element

					// const noteID = button.closest('li').getAttribute('data-id');
					// let id = parseInt(noteID);
					// this.noteCollection.removeElementByID(id);
					// this.view.deleteNotePreview(noteID)
                    // this.view.showEmptyNote(noteID); 
                    // this.view.deleteNote(noteID)
                    
				}
			} else if (modalToggleID){
                console.log(this.suggestionsCollection.getAll())
                // this.setTotalNotesCount(this.noteCollection.getLength());
            }
		} else if (li && li.tagName === 'LI'){
            // let id = li.getAttribute('data-id');

            
            // if (li.getAttribute('aria-current') === "false"){
            //     this.view.setPreviousItemUnfocused();
            //     this.view.showSelectedListItem(id);
            //     this.view.setListItemFocused(li)
            // } else {
            //     this.view.setPreviousItemUnfocused();
            //     this.view.showEmptyNote(id);                
            // }
        }
    },
    // updateNote(id, data){
	// 	let index = this.noteCollection.getIndexByID(parseInt(id));
	// 	this.noteCollection.setElementAtIndex(index, { 
    //         title: data.title,
    //         description: data.description
    //     })
	// },
	setView(view){
		this.view = view;
	},
    async setCodProd(codProd){
        this.currentCodProd = codProd
        try {
            const suggestions = await getProductSuggestions(codProd);
            this.setSuggestions(suggestions.data.data); 
        } catch(e) {
            console.log(e);
        }
    },
    setSuggestions(suggestions){
 
        let suggestionsArr = suggestions.map(el => new Suggestion(el['cod_prod'], el['percent_suggested'],
            el['user_name'], el['created_at'], el['id']))

        this.suggestionsCollection.setElements(suggestionsArr)

        this.view.setSuggestionList(this.suggestionsCollection);  
    },
    async handleSubmit(formData){
        const formDataEntries = formData.entries();
        const { percentSuggested } = Object.fromEntries(formDataEntries);
        
        try {
            const suggestion = await storeProductSuggestions({
                percentSuggested, 
                codProd: this.currentCodProd
            });

            const data = suggestion.data.data
            let suggestionObj = new Suggestion(data['cod_prod'], data['percent_suggested'],
                data['user_name'], data['created_at'], data['id'])
            this.suggestionsCollection.unshiftElement(suggestionObj)
            console.log(suggestionObj);

            this.view.unshifItem(suggestionObj)
        } catch(e){
            console.log(e)
        }
    }
}

const SuggestionModalPresenter = function (suggestionCollection = []){
    this.view = null;
	this.suggestionsCollection = new SuggestionCollection(suggestionCollection);
    this.currentCodProd = ''
}

SuggestionModalPresenter.prototype = SuggestionModalPresenterPrototype;
SuggestionModalPresenter.prototype.constructor = SuggestionModalPresenter;

export default SuggestionModalPresenter;
