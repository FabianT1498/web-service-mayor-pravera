const NotesViewPrototype = {
    init(container){
        if (!container){
            return false;
        }

        let id = container.getAttribute('id')
        this.notesContainer = container.querySelector('#' + id + '-container')
        this.notesList = container.querySelector('#' + id + '-list')
        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
    },
  
    clickEventHandlerWrapper(presenter){
        let notesContainer  = this.notesContainer.children;
        return (event) => {
            let currentNote = notesContainer[0];
            let currentNoteID = currentNote.getAttribute('data-id')
            presenter.clickOnModal({
                target: event.target,
                currentNoteID,
                data: {
                    title: currentNote.querySelector('input').value,
                    description: currentNote.querySelector('textarea').value,
                }
            })
        }
    },
    addNotePreview(note){
        this.notesList.insertAdjacentHTML('beforeend', this.getNoteItemTemplate(note))
    },
    getNoteItemTemplate(note) {
        return `
            <li 
                class="flex bg-gray-200 justify-between items-center h-1/4 w-full px-4 py-2 mb-4 first:rounded-t-lg last:rounded-b-lg hover:bg-gray-300 transition-colors ease-in-out duration-300"
                data-id="${note.id}" aria-current="false"
            >
                <div class="basis-9/12">
                    <span class="font-semibold text-lg overflow-x-hidden">${note.title !== '' ? this.truncate(note.title, 20) : 'Nota sin título'}</span>
                    <p>${this.truncate(note.description, 20)}</p>
                </div>
                <button
                    type="button"
                    data-modal="delete"
                    class="flex bg-white justify-center w-8 h-8 p-2 items-center transition-colors duration-150 rounded-full shadow-lg"
                >
                    <i class="fas text-red-600 fa-trash"></i>
                </button>
            </li>
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
        let currentNote = this.notesContainer.children[0]
        currentNote.classList.add('hidden');
        currentNote.setAttribute('data-id', note.id);
    },
    addNewEmptyNote(){
        let newNote = this.getNoteAreaInputsTemplate();
        this.notesContainer.insertAdjacentHTML('afterbegin', newNote)
    },
    deleteNotePreview(id){
        let item = this.notesList.querySelector(`li[data-id="${id}"]`)
        console.log(item)
        this.notesList.removeChild(item)
    },
    deleteNote(id){
        let note = this.notesContainer.querySelector(`[data-id="${id}"]`)
        this.notesContainer.removeChild(note)
    },
    showEmptyNote(id){
        let currentNote = this.notesContainer.querySelector(`[data-id="${id}"]`)
        currentNote.classList.add('hidden')

        let blankNote = this.notesContainer.querySelector(`[data-id=""]`)
        blankNote.classList.remove('hidden')

        this.notesContainer.insertBefore(blankNote, currentNote)
    },
    setPreviousItemUnfocused(){
        let prevNote = this.notesList.querySelector('li[aria-current="true"]');

        if (prevNote){
            prevNote.classList.remove('text-white', 'bg-blue-600', 'border-b', 'border-gray-200','focus:outline-none', 'hover:bg-blue-700')
            prevNote.setAttribute('aria-current', "false")
        }
    },
    setListItemFocused(li){
        li.setAttribute('aria-current', "true")
        li.classList.add('text-white', 'bg-blue-600', 'border-b', 'border-gray-200','focus:outline-none', 'hover:bg-blue-700');
    },
    showSelectedListItem(id){
        let selectedNote = this.notesContainer.querySelector(`[data-id="${id}"]`)
        selectedNote.classList.remove('hidden')

        this.notesContainer.children[0].classList.add('hidden')
        this.notesContainer.insertBefore(selectedNote, this.notesContainer.children[0])
    },
    truncate(str, n){
        return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
    }
}

const NotesView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

NotesView.prototype = NotesViewPrototype;
NotesView.prototype.constructor = NotesView;

export default NotesView;