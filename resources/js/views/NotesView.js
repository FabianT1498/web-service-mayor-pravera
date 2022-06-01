const NotesViewPrototype = {
    init(container){
        if (!container){
            return false;
        }


        let id = container.getAttribute('id')
        this.notesContainer = container.querySelector('#' + id + '-container')
        this.notesList = container.querySelector('#' + id + '-list')
        // container.addEventListener("keypress", this.keyPressEventHandlerWrapper(this.presenter))
        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
    },
    keyPressEventHandlerWrapper(presenter){
        return (event) => {
            event.preventDefault();
            presenter.keyPressedOnModal({
                target: event.target,
                key: event.key || event.keyCode
            })
        }
    },
    clickEventHandlerWrapper(presenter){
        let currentNote = this.notesContainer.querySelector('div[data-current-note=true]')
    
        return (event) => {
            presenter.clickOnModal({
                target: event.target,
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
                class="flex bg-gray-200 justify-between items-center h-1/4 w-full px-4 py-2 mb-4 hover:bg-gray-300 transition-colors ease-in-out duration-300"
                data-id="${note.id}"
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