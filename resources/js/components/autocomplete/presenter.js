
import { timerDelay } from '_utilities/timerDelay'

const AutocompletePresenterPrototype = {
	onKeyEvent(value){
        if (value === '') {
			this.autocompleteCb(true)
			this.view.hideResultsContainer()
			this.view.resetAutocomplete();
		} else {
			this.autocompleteCb(false, value)
		}
    },
	setView(view){
		this.view = view;
	},
	submitData(data){
        this.service(data).then((res) => {
			let data = res.data;

			this.view.showResults(data)
			
        }).catch(err => {
            console.log(err);
        })
    },
}

const AutocompletePresenter = function (service){
    this.view = null;
	this.service = service
	this.autocompleteCb = timerDelay(this.submitData, 500)
}

AutocompletePresenter.prototype = AutocompletePresenterPrototype;
AutocompletePresenter.prototype.constructor = AutocompletePresenter;

export default AutocompletePresenter;