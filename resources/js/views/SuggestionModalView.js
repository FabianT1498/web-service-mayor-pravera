import { percentageInput } from '_utilities/numericInput';

const SuggestionModalViewPrototype = {
    init(container){
        if (!container){
            return false;
        }

        let id = container.getAttribute('id')
        
        this.suggestionModalContainer = container.querySelector('#' + id + '-container')
        this.suggestionLoading = container.querySelector('#' + id + '-loading')
        this.suggestionForm = container.querySelector('#' + id + '-form')
        this.addSuggestionBtn =  this.suggestionForm.querySelector('button[data-modal="add"]')
        this.percentInput = this.suggestionForm.querySelector('#percentSuggested')

        // container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
        // container.addEventListener("keypress", this.keypressEventHandler);

        percentageInput.mask(this.percentInput);



        console.log(percentageInput)
        
        this.addSuggestionBtn.addEventListener("click", this.handleInputSubmit())
    },
    handleInputSubmit(){
        let self = this;
        return (event) => {
            const formData = new FormData(self.suggestionForm);
            this.presenter.handleSubmit(formData)
        }
    },
    keypressEventHandler(event){
        let key = event.key || event.keyCode;
        if (key === 13 || key === 'Enter'){
            event.preventDefault()        
        }
    },
    clickEventHandlerWrapper(presenter){
        // let notesContainer  = this.suggestionModalContainer.children;
        // return (event) => {
        //     let currentNote = notesContainer[0];
        //     let currentNoteID = currentNote.getAttribute('data-id')
        //     presenter.clickOnModal({
        //         target: event.target,
        //         currentNoteID,
        //         data: {
        //             title: currentNote.querySelector('input').value,
        //             description: currentNote.querySelector('textarea').value,
        //         }
        //     })
        // }
    },
    setSuggestionList(suggestionCollection){
    
        let items = suggestionCollection.getAll().map(el => this.getSuggestionItemTemplate(el))
        items = items.join('')
        console.log(items);
        this.suggestionModalContainer.insertAdjacentHTML('beforeend', items)
    },
    unshifItem(el){
        let item = this.getSuggestionItemTemplate(el)
        this.suggestionModalContainer.insertAdjacentHTML('beforeend', item)
    },
    addNotePreview(note){
        this.notesList.insertAdjacentHTML('beforeend', this.getNoteItemTemplate(note))
    },
    getSuggestionItemTemplate(suggestion) {
        return `
            <tr data-id="${suggestion.id}" aria-current="false"
            >                
                <td class="text-center">${suggestion.createdAt}</td>
                <td class="text-center">${suggestion.percentSuggested}</td>
                <td class="text-center">${suggestion.username}</td>
            </tr>
        `
    },
    getNoteAreaInputsTemplate(){
        return `
            <div class="flex flex-col justify-between h-full" data-id="">
                <input type="text" placeholder="Título" name="note_title[]" class="font-light text-xl text-gray-500 rounded-t-md min-w-0 border-solid border-0 border-b-2 border-blue-400 shadow-lg focus:outline-none focus:shadow-none
                    focus:border-blue-600 focus:ring-0">
                <textarea class="w-full resize-none basis-4/5 border-none border-0 shadow-lg focus:shadow-none focus:border-none focus:outline-none focus:ring-0" placeholder="Descripción"  name="note_description[]"
                    ></textarea>
            </div>
        `
    },
    hideSavedNote(note){
        let currentNote = this.suggestionModalContainer.children[0]
        currentNote.classList.add('hidden');
        currentNote.setAttribute('data-id', note.id);
    },
    addNewEmptyNote(){
        let newNote = this.getNoteAreaInputsTemplate();
        this.notesContainer.insertAdjacentHTML('afterbegin', newNote)
    },
    deleteNotePreview(id){
        let item = this.notesList.querySelector(`li[data-id="${id}"]`)
        this.notesList.removeChild(item)
    },
    deleteNote(id){
        let note = this.suggestionModalContainer.querySelector(`[data-id="${id}"]`)
        this.suggestionModalContainer.removeChild(note)
    },
    showEmptyNote(id){
        let currentNote = this.suggestionModalContainer.querySelector(`[data-id="${id}"]`)

        if (currentNote){
            currentNote.classList.add('hidden')
    
            let blankNote = this.suggestionModalContainer.querySelector(`[data-id=""]`)
            blankNote.classList.remove('hidden')
    
            this.suggestionModalContainer.insertBefore(blankNote, currentNote)
        }
    },
    setPreviousItemUnfocused(){
        let prevNote = this.notesList.querySelector('li[aria-current="true"]');

        if (prevNote){
            prevNote.classList.remove('text-white', 'bg-blue-600', 'border-b', 'border-gray-200','focus:outline-none', 'hover:bg-blue-700')
            prevNote.classList.add('bg-gray-200')
            prevNote.setAttribute('aria-current', "false")
        }
    },
    setListItemFocused(li){
        li.setAttribute('aria-current', "true")
        li.classList.remove('bg-gray-200')
        li.classList.add('text-white', 'bg-blue-600', 'border-b', 'border-gray-200','focus:outline-none', 'hover:bg-blue-700');
    },
    showSelectedListItem(id){
        let selectedNote = this.notesContainer.querySelector(`[data-id="${id}"]`)
        selectedNote.classList.remove('hidden')

        this.notesContainer.children[0].classList.add('hidden')
        this.notesContainer.insertBefore(selectedNote, this.notesContainer.children[0])
    },
    updateListItemContent(id, data){
        let noteEl = this.notesList.querySelector(`[data-id="${id}"]`)

        noteEl.querySelector('span').innerHTML =  data.title !== '' ? this.truncate(data.title, 20) : 'Nota sin título'
        noteEl.querySelector('p').innerHTML = this.truncate(data.description, 20)

    },
    truncate(str, n){
        return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
    }
}

const SuggestionModalView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

SuggestionModalView.prototype = SuggestionModalViewPrototype;
SuggestionModalView.prototype.constructor = SuggestionModalView;

export default SuggestionModalView;