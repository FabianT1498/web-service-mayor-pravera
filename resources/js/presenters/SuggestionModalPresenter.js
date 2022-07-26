import SuggestionCollection from '_collections/suggestionCollection'
import Suggestion from '_models/Suggestion'
import { SUGGESTION_STATUS } from '_constants/productSuggestion';
import { ERROR, SUCCESS } from '_constants/message-status'
import { storeProductSuggestions, getProductSuggestions } from '_services/products';

const SuggestionModalPresenterPrototype = {
	clickOnModal({ target }) {
		const button = target.closest('button');

		if(button && button.tagName === 'BUTTON'){
			
            const modalToggleID = button.getAttribute('data-modal-toggle');
            
			if (modalToggleID){
                this.view.hideContent();
                this.view.enableSubmitBtn()
            }
		}
    },
	setView(view){
		this.view = view;
	},
    async setCodProd(codProd){
        this.currentCodProd = codProd
        try {
            this.view.showLoading();
            const suggestions = await getProductSuggestions(codProd);
            const data = suggestions.data.data;
            this.view.hideLoading();
            this.view.showContent();
            this.view.emptySuggestion();
            this.setSuggestions(data);
            this.view.setSuggestionList(this.suggestionsCollection);

            if(this.suggestionsCollection.getLength() > 0
                    &&  SUGGESTION_STATUS[this.suggestionsCollection.getFirst().status] === SUGGESTION_STATUS.PROCESSING){
                this.view.disableSubmitBtn()
                this.view.showMessage('Para crear una nueva sugerencia debe esperar a que sea procesada la sugerencia anterior')
            }
            
        } catch(e) {
            console.log(e);
        }
    },
    setSuggestions(suggestions){
        let suggestionsArr = suggestions.map(el => new Suggestion(el['cod_prod'], el['percent_suggested'],
            el['user_name'], el['created_at'], el['status'], el['id']))
        this.suggestionsCollection.setElements(suggestionsArr)
    },
    async handleSubmit(formData){
        const formDataEntries = formData.entries();
        const { percentSuggested, database } = Object.fromEntries(formDataEntries);
        
        try {
            const suggestion = await storeProductSuggestions({
                percentSuggested, 
                database,
                codProd: this.currentCodProd
            });

            const data = suggestion.data.data
            let suggestionObj = new Suggestion(data['cod_prod'], data['percent_suggested'],
                data['user_name'], data['created_at'], data['status'], data['id'])
            this.suggestionsCollection.unshiftElement(suggestionObj)
            
            this.view.unshifItem(suggestionObj)
            this.view.disableSubmitBtn();
            this.view.showMessage('Sugerencia creada con exito, para crear una nueva sugerencia debe esperar a que sea procesada la sugerencia.',
                SUCCESS)
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
