import { percentageInput } from '_utilities/numericInput';

import { ERROR, SUCCESS } from '_constants/message-status'
import { SUGGESTION_STATUS } from '_constants/productSuggestion';


const SuggestionModalViewPrototype = {
    init(container){
        if (!container){
            return false;
        }

        let id = container.getAttribute('id')
        
        this.suggestionModalContainer = container.querySelector('#' + id + '-container')
        this.suggestionLoading = container.querySelector('#' + id + '-loading')

        this.suggestionInfo = container.querySelector('#' + id + '-info')
        
        this.suggestionMessage = this.suggestionInfo.querySelector('#' + id + '-message')

        this.suggestionForm = container.querySelector('#' + id + '-form')
        this.addSuggestionBtn =  this.suggestionForm.querySelector('button[data-modal="add"]')
        this.percentInput = this.suggestionForm.querySelector('#percentSuggested')

        container.addEventListener("click", this.clickEventHandlerWrapper(this.presenter));
        this.percentInput.addEventListener("keypress", this.keypressEventHandler);

        percentageInput.mask(this.percentInput);
        this.addSuggestionBtn.addEventListener("click", this.handleInputSubmit())
    },
    handleInputSubmit(){
        let self = this;
        return (event) => {
            const formData = new FormData(self.suggestionForm);
            this.presenter.handleSubmit(formData)
        }
    },
    showLoading(){
        this.suggestionLoading.classList.remove('hidden')
    },
    hideLoading(){
        this.suggestionLoading.classList.add('hidden')
    },
    showContent(){
        this.suggestionInfo.classList.remove('hidden')
    },
    hideContent(){
        this.suggestionInfo.classList.add('hidden')
    },
    keypressEventHandler(event){
        let key = event.key || event.keyCode;
        if (key === 13 || key === 'Enter'){
            event.preventDefault()        
        }
    },
    clickEventHandlerWrapper(presenter){
        return (event) => {
            presenter.clickOnModal({
                target: event.target,
            })
        }
    },
    setSuggestionList(suggestionCollection){
    
        let items = suggestionCollection.getAll().map(el => this.getSuggestionItemTemplate(el))
        items = items.join('')
        this.suggestionModalContainer.insertAdjacentHTML('beforeend', items)
    },
    unshifItem(el){
        let item = this.getSuggestionItemTemplate(el)
        this.suggestionModalContainer.insertAdjacentHTML('beforeend', item)
    },
    disableSubmitBtn(){
        this.addSuggestionBtn.disabled = true;
        this.addSuggestionBtn.classList.add('bg-gray-400')
    },
    enableSubmitBtn(){
        this.addSuggestionBtn.disabled = false;
        this.addSuggestionBtn.classList.remove('bg-gray-400')
    },
    showMessage(message, status = ''){
        this.suggestionMessage.innerHTML = message
        this.suggestionMessage.classList.remove('hidden')

        if (status === ERROR){
            this.suggestionMessage.classList.add('error')
            this.suggestionMessage.classList.remove('success')
        } else if (status === SUCCESS){
            this.suggestionMessage.classList.add('success')
            this.suggestionMessage.classList.remove('error')
        }

    },
    getSuggestionItemTemplate(suggestion) {
        return `
            <tr data-id="${suggestion.id}" aria-current="false"
            >                
                <td class="text-center">${suggestion.createdAt}</td>
                <td class="text-center">${suggestion.percentSuggested}</td>
                <td class="text-center">${suggestion.username}</td>
                <td class="text-center">${SUGGESTION_STATUS[suggestion.status]}</td>
            </tr>
        `
    },
    emptySuggestion(){
        this.suggestionModalContainer.innerHTML = ''
    }
}

const SuggestionModalView = function (presenter){
    this.presenter = presenter;
    this.presenter.setView(this);
}

SuggestionModalView.prototype = SuggestionModalViewPrototype;
SuggestionModalView.prototype.constructor = SuggestionModalView;

export default SuggestionModalView;